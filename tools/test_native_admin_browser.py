"""Optional Windows Edge regression with isolated SQLite.

Install playwright-core outside application dependencies, then set
SDM_PLAYWRIGHT_MODULE to its absolute directory and SDM_BROWSER_PATH to Edge.
SDM_BROWSER_CASE=token or pagination selects one regression; default runs both.
Run from repo root: python tools/test_native_admin_browser.py
Port 8766 must be free and Laravel config cache must be cleared first.
"""
import os
from pathlib import Path
import subprocess
import tempfile
import time
import urllib.request

def main():
    root = Path(__file__).resolve().parent.parent
    with tempfile.TemporaryDirectory(prefix='sdm-browser-') as scratch:
        database = Path(scratch) / 'test.sqlite'
        database.touch()
        env = os.environ.copy()
        env.update({'APP_ENV': 'local', 'APP_DEBUG': 'false', 'DB_CONNECTION': 'sqlite',
                    'DB_DATABASE': str(database), 'SDM_DB_CONNECTION': 'sqlite',
                    'SDM_DB_DATABASE': str(database), 'SDM_BROWSER_TEST_DATABASE': str(database), 'SESSION_DRIVER': 'file',
                    'SESSION_COOKIE': 'sdm_browser_test', 'CACHE_STORE': 'array',
                    'APP_URL': 'http://127.0.0.1:8766'})
        subprocess.check_call(['php', 'tools/native_browser_seed.php'], cwd=str(root), env=env)
        with (Path(scratch) / 'server.log').open('w') as log:
            server = subprocess.Popen(['php', 'artisan', 'serve', '--host=127.0.0.1',
                                       '--port=8766', '--no-reload'], cwd=str(root), env=env,
                                      stdout=log, stderr=log, creationflags=0x08000000)
            try:
                for attempt in range(100):
                    try:
                        urllib.request.urlopen('http://127.0.0.1:8766/up', timeout=1)
                        break
                    except Exception:
                        time.sleep(.1)
                else:
                    raise RuntimeError('Laravel test server did not start')
                subprocess.check_call(['node', 'tools/test_native_admin_browser.cjs'], cwd=str(root), env=env)
            finally:
                subprocess.run(['taskkill', '/PID', str(server.pid), '/T', '/F'],
                               stdout=subprocess.PIPE, stderr=subprocess.PIPE)
                server.wait(timeout=10)


if __name__ == "__main__":
    main()
