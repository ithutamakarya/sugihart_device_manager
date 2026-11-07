"""Convert this project's MySQL dump to transactional PostgreSQL SQL (stdlib only)."""

import argparse
import hashlib
import re
from pathlib import Path


def split_sql(text, delimiter=';', comments=False):
    """Split outside quoted literals and parentheses; never execute source SQL."""
    chunks, current = [], []
    quote, depth, index = None, 0, 0
    while index < len(text):
        char = text[index]
        if quote:
            current.append(char)
            if char == '\\' and quote != '`':
                index += 1
                if index >= len(text):
                    raise ValueError('Incomplete MySQL string escape.')
                current.append(text[index])
            elif char == quote:
                if index + 1 < len(text) and text[index + 1] == quote:
                    index += 1
                    current.append(text[index])
                else:
                    quote = None
        elif comments and (text.startswith('-- ', index) or text.startswith('--\n', index) or char == '#'):
            end = text.find('\n', index)
            index = len(text) if end < 0 else end
            current.append(' ')
            continue
        elif comments and text.startswith('/*', index):
            end = text.find('*/', index + 2)
            if end < 0:
                raise ValueError('Unterminated MySQL comment.')
            index = end + 2
            current.append(' ')
            continue
        elif char in "'\"`":
            quote = char
            current.append(char)
        elif char == '(':
            depth += 1
            current.append(char)
        elif char == ')':
            depth -= 1
            if depth < 0:
                raise ValueError('Unbalanced SQL parentheses.')
            current.append(char)
        elif char == delimiter and depth == 0:
            if ''.join(current).strip():
                chunks.append(''.join(current).strip())
            current = []
        else:
            current.append(char)
        index += 1
    if quote or depth:
        raise ValueError('Unterminated SQL literal or parentheses.')
    if ''.join(current).strip():
        chunks.append(''.join(current).strip())
    return chunks


def mysql_literal(value):
    value = value.strip()
    if value.upper() == 'NULL':
        return None
    if re.fullmatch(r'[+-]?\d+', value):
        return int(value)
    if not (len(value) >= 2 and value[0] == "'" and value[-1] == "'"):
        raise ValueError('Unsupported SQL value; expected a string, integer or NULL.')
    result, index = [], 1
    escapes = {'0': '\0', 'b': '\b', 'n': '\n', 'r': '\r', 't': '\t', 'Z': '\x1a'}
    while index < len(value) - 1:
        char = value[index]
        if char == '\\':
            index += 1
            if index >= len(value) - 1:
                raise ValueError('Incomplete string escape.')
            escaped = value[index]
            # MySQL retains the backslash for escaped LIKE wildcards.
            result.append('\\' + escaped if escaped in '%_' else escapes.get(escaped, escaped))
        elif char == "'":
            if index + 1 >= len(value) - 1 or value[index + 1] != "'":
                raise ValueError('Unescaped string quote.')
            result.append("'")
            index += 1
        else:
            result.append(char)
        index += 1
    result = ''.join(result)
    if '\0' in result:
        raise ValueError('PostgreSQL text cannot contain NUL bytes; conversion stopped without altering data.')
    return result


def identifier(value):
    if not re.fullmatch(r'[a-zA-Z_][a-zA-Z0-9_]*', value):
        raise ValueError('Unsupported SQL identifier.')
    return '"' + value + '"'


def pg_literal(value):
    if value is None:
        return 'NULL'
    if isinstance(value, int):
        return str(value)
    return "'" + value.replace("'", "''") + "'"


def parse_dump(text):
    tables = {}
    for number, statement in enumerate(split_sql(text, comments=True), 1):
        if re.match(r'^(DROP TABLE IF EXISTS|LOCK TABLES|UNLOCK TABLES)\b', statement, re.I):
            continue
        create = re.fullmatch(r'CREATE TABLE `([a-zA-Z0-9_]+)`\s*\((.*)\)\s*ENGINE=([^;]+)', statement, re.S | re.I)
        if create:
            name, body, options = create.groups()
            identifier(name)
            if name in tables:
                raise ValueError('Duplicate table definition: ' + name)
            table = {'columns': [], 'primary_key': [], 'rows': [], 'auto_increment': None}
            auto = re.search(r'\bAUTO_INCREMENT=(\d+)\b', options, re.I)
            if auto:
                table['auto_increment'] = int(auto.group(1))
            for definition in split_sql(body, ','):
                primary = re.fullmatch(r'PRIMARY KEY\s*\(([^)]+)\)(?: USING BTREE)?', definition, re.I)
                if primary:
                    table['primary_key'] = [part.strip().strip('`') for part in primary.group(1).split(',')]
                    continue
                column = re.fullmatch(r'`([a-zA-Z0-9_]+)`\s+(bigint|int|varchar|timestamp)(?:\((\d+)\))?(.*)', definition, re.S | re.I)
                if not column:
                    raise ValueError('Unsupported column or index in table ' + name)
                col_name, kind, size, attributes = column.groups()
                identifier(col_name)
                kind = kind.lower()
                attributes = re.sub(r'\s+COLLATE\s+[a-zA-Z0-9_]+', '', attributes, flags=re.I).strip()
                unsigned = bool(re.search(r'\bunsigned\b', attributes, re.I))
                identity = bool(re.search(r'\bAUTO_INCREMENT\b', attributes, re.I))
                attributes = re.sub(r'\b(?:unsigned|AUTO_INCREMENT)\b', '', attributes, flags=re.I).strip()
                nullable = not bool(re.search(r'\bNOT NULL\b', attributes, re.I))
                default = re.search(r"\bDEFAULT\s+(NULL|'(?:[^'\\]|\\.|'')*'|[+-]?\d+)", attributes, re.I)
                default_value = mysql_literal(default.group(1)) if default else None
                remaining = re.sub(r"\bDEFAULT\s+(NULL|'(?:[^'\\]|\\.|'')*'|[+-]?\d+)", '', attributes, flags=re.I)
                remaining = re.sub(r'\b(?:NOT NULL|NULL)\b', '', remaining, flags=re.I).strip()
                if remaining or (identity and kind not in ('int', 'bigint')):
                    raise ValueError('Unsupported column attributes in table ' + name)
                table['columns'].append({'name': col_name, 'kind': kind, 'size': int(size) if size else None,
                                         'nullable': nullable, 'unsigned': unsigned, 'identity': identity,
                                         'has_default': default is not None, 'default': default_value})
            if any(column not in [col['name'] for col in table['columns']] for column in table['primary_key']):
                raise ValueError('Unknown primary key column in ' + name)
            tables[name] = table
            continue
        insert = re.fullmatch(r'INSERT INTO `([a-zA-Z0-9_]+)`\s+VALUES\s+(.+)', statement, re.S | re.I)
        if not insert:
            raise ValueError('Unsupported SQL statement number ' + str(number))
        name, values = insert.groups()
        if name not in tables:
            raise ValueError('INSERT precedes table definition: ' + name)
        for row in split_sql(values, ','):
            if not (row.startswith('(') and row.endswith(')')):
                raise ValueError('Unsupported INSERT row in ' + name)
            parsed = [mysql_literal(value) for value in split_sql(row[1:-1], ',')]
            if len(parsed) != len(tables[name]['columns']):
                raise ValueError('Column count mismatch in table ' + name)
            for column, value in zip(tables[name]['columns'], parsed):
                if value is None:
                    if not column['nullable']:
                        raise ValueError('Unexpected NULL in ' + name + '.' + column['name'])
                elif column['kind'] in ('bigint', 'int'):
                    bits = 64 if column['kind'] == 'bigint' else 32
                    if not isinstance(value, int) or not -(2 ** (bits - 1)) <= value < 2 ** (bits - 1) or (column['unsigned'] and value < 0):
                        raise ValueError('Integer outside PostgreSQL range in ' + name + '.' + column['name'])
                elif not isinstance(value, str) or (column['kind'] == 'varchar' and len(value) > column['size']):
                    raise ValueError('Invalid string in ' + name + '.' + column['name'])
                elif column['kind'] == 'timestamp' and not re.fullmatch(r'\d{4}-\d\d-\d\d \d\d:\d\d:\d\d(?:\.\d+)?', value):
                    raise ValueError('Unsupported timestamp in ' + name + '.' + column['name'])
                elif column['kind'] == 'timestamp' and value.startswith('0000-'):
                    raise ValueError('Zero timestamp cannot be preserved in PostgreSQL.')
            tables[name]['rows'].append(parsed)
    if not tables:
        raise ValueError('No supported tables found.')
    return tables


def render_postgresql(tables, source_hash, schema='public'):
    identifier(schema)
    output = [
        '-- PostgreSQL conversion of device.sql; original values are preserved.',
        '-- Source SHA256: ' + source_hash,
        '-- Restore into a NEW empty device-manager database; existing tables cause an error.',
        '-- No DROP/TRUNCATE statements. All tables and data are imported in one transaction.',
        'BEGIN;', "SET LOCAL client_encoding = 'UTF8';", 'SET LOCAL standard_conforming_strings = on;',
        'CREATE SCHEMA IF NOT EXISTS ' + identifier(schema) + ';',
    ]
    for name, table in tables.items():
        qualified = identifier(schema) + '.' + identifier(name)
        columns = []
        for col in table['columns']:
            kind = {'int': 'integer', 'bigint': 'bigint', 'timestamp': 'timestamp without time zone',
                    'varchar': 'varchar(' + str(col['size']) + ')'}[col['kind']]
            line = '    ' + identifier(col['name']) + ' ' + kind
            if col['identity']:
                line += ' GENERATED BY DEFAULT AS IDENTITY'
            if not col['nullable']:
                line += ' NOT NULL'
            if col['has_default'] and not col['identity']:
                line += ' DEFAULT ' + pg_literal(col['default'])
            if col['unsigned']:
                line += ' CHECK (' + identifier(col['name']) + ' >= 0)'
            columns.append(line)
        if table['primary_key']:
            columns.append('    PRIMARY KEY (' + ', '.join(identifier(col) for col in table['primary_key']) + ')')
        output.extend(['', 'CREATE TABLE ' + qualified + ' (', ',\n'.join(columns), ');'])
        for offset in range(0, len(table['rows']), 100):
            output.append('INSERT INTO ' + qualified + ' (' + ', '.join(identifier(col['name']) for col in table['columns']) + ') VALUES')
            output.append(',\n'.join('    (' + ', '.join(pg_literal(value) for value in row) + ')'
                                    for row in table['rows'][offset:offset + 100]) + ';')
        for index, col in enumerate(table['columns']):
            if col['identity']:
                next_id = max([1, table['auto_increment'] or 1] + [row[index] + 1 for row in table['rows']])
                output.append('SELECT setval(pg_get_serial_sequence(' + pg_literal(qualified) + ', '
                              + pg_literal(col['name']) + '), ' + str(next_id) + ', false);')
    output.extend(['', 'COMMIT;', ''])
    return '\n'.join(output)


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('source', type=Path)
    parser.add_argument('--output', type=Path, default=Path('device.postgresql.sql'))
    parser.add_argument('--schema', default='public')
    args = parser.parse_args()
    if args.source.resolve() == args.output.resolve():
        parser.error('Source and output must be different files.')
    raw = args.source.read_bytes()
    tables = parse_dump(raw.decode('utf-8-sig'))
    converted = render_postgresql(tables, hashlib.sha256(raw).hexdigest(), args.schema)
    args.output.write_text(converted, encoding='utf-8')
    print('Created ' + str(args.output))
    for name, table in tables.items():
        print(name + ': ' + str(len(table['rows'])) + ' rows')


if __name__ == '__main__':
    main()
