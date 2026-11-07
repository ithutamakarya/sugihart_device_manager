import hashlib
import json
import tempfile
import unittest
import zipfile
from pathlib import Path

from build_native_handoff import APPLICATION_FILES, build_archive


class NativeHandoffTest(unittest.TestCase):
    def fixture(self, root):
        for relative in APPLICATION_FILES:
            path = root / relative
            path.parent.mkdir(parents=True, exist_ok=True)
            path.write_text('native source ' + relative, encoding='utf-8')
        (root / 'PANDUAN_MERGE_MANUAL_ABSENSI_HUTAMAKARYA.md').write_text('# Native merge guide', encoding='utf-8')
        assets = root / 'public/vendor/sugihart-device-manager'
        assets.mkdir(parents=True)
        (assets / 'style.css').write_text('body { color: black; }')
        (assets / 'demo.php').write_text('private executable')
        (root / '.env').write_text('private credentials')
        (root / 'device.sql').write_text('private data')
        (root / 'composer.json').write_text('host global')

    def test_archive_extracts_nine_application_files_assets_and_manifest_without_host_globals(self):
        with tempfile.TemporaryDirectory() as temporary:
            root = Path(temporary)
            self.fixture(root)
            archive = build_archive(root, root / 'integration/native.zip')
            with zipfile.ZipFile(str(archive)) as zipped:
                names = set(zipped.namelist())
                self.assertEqual(len(APPLICATION_FILES), 9)
                self.assertTrue(set(APPLICATION_FILES).issubset(names))
                self.assertIn('public/vendor/sugihart-device-manager/style.css', names)
                self.assertFalse({'.env', 'device.sql', 'composer.json', 'bootstrap/providers.php'} & names)
                self.assertFalse(any(name.endswith('.php') and name.startswith('public/') for name in names))
                manifest = json.loads(zipped.read('sugihart-native-manifest.json').decode('utf-8'))
                self.assertEqual(manifest['application_files'], list(APPLICATION_FILES))
                self.assertEqual({item['path'] for item in manifest['files']}, names - {'sugihart-native-manifest.json'})
                for entry in manifest['files']:
                    content = zipped.read(entry['path'])
                    self.assertEqual(hashlib.sha256(content).hexdigest(), entry['sha256'])
                destination = root / 'host'
                zipped.extractall(str(destination))
                self.assertEqual((destination / APPLICATION_FILES[0]).read_text(), 'native source ' + APPLICATION_FILES[0])

    def test_missing_application_file_prevents_incomplete_archive(self):
        with tempfile.TemporaryDirectory() as temporary:
            root = Path(temporary)
            self.fixture(root)
            (root / APPLICATION_FILES[0]).unlink()
            with self.assertRaises(FileNotFoundError):
                build_archive(root, root / 'integration/native.zip')
            self.assertFalse((root / 'integration/native.zip').exists())

    def test_archive_is_reproducible_for_the_same_source(self):
        with tempfile.TemporaryDirectory() as temporary:
            root = Path(temporary)
            self.fixture(root)
            first = build_archive(root, root / 'one.zip').read_bytes()
            second = build_archive(root, root / 'two.zip').read_bytes()
            self.assertEqual(first, second)


if __name__ == '__main__':
    unittest.main()
