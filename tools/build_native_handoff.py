"""Build the native Sugihart handoff without host-global files or private data."""

import argparse
import hashlib
import json
import os
import tempfile
import zipfile
from pathlib import Path


APPLICATION_FILES = (
    'app/Http/Controllers/Sugihart/DeviceManagerController.php',
    'app/Models/Sugihart/User.php',
    'app/Http/Middleware/Sugihart/Authenticate.php',
    'app/Providers/SugihartServiceProvider.php',
    'config/sugihart-device-manager.php',
    'routes/sugihart.php',
    'database/migrations/sugihart/2026_11_06_000000_create_device_manager_tables.php',
    'resources/views/sugihart/admin.blade.php',
    'resources/views/sugihart/kios.blade.php',
)
GUIDE = 'PANDUAN_MERGE_MANUAL_ABSENSI_HUTAMAKARYA.md'
ASSETS = 'public/vendor/sugihart-device-manager'
EXCLUDED_SUFFIXES = {'.php', '.phtml', '.phar', '.sql', '.backup', '.key', '.pem', '.exe', '.dll', '.bat', '.cmd', '.sh', '.py', '.rb', '.zip'}


def build_archive(root, output):
    root, output = Path(root).resolve(), Path(output).absolute()
    entries = {}

    def include(path, archive_name):
        if path.is_symlink() or root not in path.resolve().parents:
            raise ValueError('Symlink or path outside source root: ' + archive_name)
        if not path.is_file():
            raise FileNotFoundError(str(path))
        entries[archive_name] = path.read_bytes()

    for relative in APPLICATION_FILES:
        include(root / relative, relative)
    include(root / GUIDE, 'docs/SUGIHART_NATIVE_MERGE.md')
    assets = root / ASSETS
    if not assets.is_dir():
        raise FileNotFoundError(str(assets))
    for path in sorted(assets.rglob('*')):
        if path.is_symlink():
            raise ValueError('Symlinks are not allowed in native assets.')
        if path.is_file() and path.suffix.lower() not in EXCLUDED_SUFFIXES and not any(part.startswith('.') for part in path.relative_to(assets).parts):
            include(path, path.relative_to(root).as_posix())
    if not any(name.startswith(ASSETS + '/') for name in entries):
        raise ValueError('No static assets found.')
    manifest = {
        'schema_version': 1,
        'application_files': list(APPLICATION_FILES),
        'files': [{'path': name, 'sha256': hashlib.sha256(content).hexdigest(), 'bytes': len(content)} for name, content in sorted(entries.items())],
    }
    entries['sugihart-native-manifest.json'] = (json.dumps(manifest, indent=2) + '\n').encode('utf-8')
    output.parent.mkdir(parents=True, exist_ok=True)
    descriptor, temporary = tempfile.mkstemp(prefix='.native-', suffix='.zip', dir=str(output.parent))
    os.close(descriptor)
    try:
        with zipfile.ZipFile(temporary, 'w', compression=zipfile.ZIP_DEFLATED) as archive:
            for name, content in sorted(entries.items()):
                entry = zipfile.ZipInfo(name, date_time=(1980, 1, 1, 0, 0, 0))
                entry.compress_type = zipfile.ZIP_DEFLATED
                entry.external_attr = 0o100644 << 16
                archive.writestr(entry, content)
        os.replace(temporary, str(output))
    finally:
        if os.path.exists(temporary):
            os.unlink(temporary)
    return output


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--root', type=Path, default=Path(__file__).resolve().parents[1])
    parser.add_argument('--output', type=Path, default=Path('integration/sugihart-native.zip'))
    args = parser.parse_args()
    output = build_archive(args.root, args.output)
    print('Created ' + str(output) + ' (' + str(output.stat().st_size) + ' bytes)')


if __name__ == '__main__':
    main()
