// Optional Edge regression: run with tools/test_native_admin_browser.py.
const { chromium } = require(process.env.SDM_PLAYWRIGHT_MODULE || 'playwright-core');
const assert = require('node:assert/strict');

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.SDM_BROWSER_PATH, headless: true });
  try {
    const page = await browser.newPage();
    const origin = 'http://127.0.0.1:8766';
    const base = origin + '/sugihart_device_manager/public';
    await page.route('**/*', route => new URL(route.request().url()).origin === origin ? route.continue() : route.abort());
    await page.goto(base + '/login', { waitUntil: 'networkidle' });
    await page.locator('input[name=email]').fill('browser@example.test');
    await page.locator('input[name=password]').fill('browser-password');
    await Promise.all([page.waitForURL(base), page.locator('button[type=submit]').click()]);
    const modal = page.locator('#modal-success');
    async function open(path) {
      await page.goto(base + path, { waitUntil: 'networkidle' });
    }
    async function save() {
      await Promise.all([page.waitForNavigation(), modal.locator('button[type=submit]').click()]);
    }
    async function lastPage() {
      await page.evaluate(() => window.jQuery('#datable_1').DataTable().page('last').draw(false));
    }
    async function confirmDelete(row) {
      await row.locator('.delete_data').click();
      await page.locator('.sweet-alert.visible').waitFor({ timeout: 2000 });
      await Promise.all([page.waitForNavigation(), page.locator('.sweet-alert button.confirm').click()]);
    }
    if (!process.env.SDM_BROWSER_CASE || process.env.SDM_BROWSER_CASE === 'token') {
      await open('/main/vendor');
      await page.locator('tr').filter({ hasText: 'Browser HK' }).locator('.edit_data').click();
      await modal.waitFor({ state: 'visible', timeout: 2000 });
      assert.equal(await modal.locator('input[name=token]').inputValue(), 'browser-vendor');
      await modal.locator('input[name=name]').fill('Renamed HK');
      await save();
      const row = page.locator('tr').filter({ hasText: 'Renamed HK' });
      assert.ok((await row.textContent()).includes('browser-vendor'));
      const response = await page.request.get(base + '/api/get_device/browser-vendor');
      assert.ok(Array.isArray(await response.json()), 'Old API token must still resolve after editing vendor name');
      console.log('PASS vendor name-only edit preserves token and existing API URL');
    }
    if (!process.env.SDM_BROWSER_CASE || process.env.SDM_BROWSER_CASE === 'pagination') {
      for (const [path, name, renamed] of [
        ['/main/vendor', 'Page Vendor 12', 'Renamed Last Vendor'],
        ['/main/user_list', 'Page User 12', 'Renamed Last User'],
      ]) {
        await open(path);
        await lastPage();
        await page.locator('tr').filter({ hasText: name }).locator('.edit_data').click();
        await modal.waitFor({ state: 'visible', timeout: 2000 });
        await modal.locator('input[name=name]').fill(renamed);
        await save();
        await lastPage();
        await confirmDelete(page.locator('tr').filter({ hasText: renamed }));
        await lastPage();
        assert.equal(await page.locator('tr').filter({ hasText: renamed }).count(), 0);
      }
      await open('/main/device/1');
      await page.locator('#home_tab_6').click();
      await lastPage();
      let row = page.locator('tr').filter({ hasText: 'Page Camera 12' });
      await row.locator('.create_sub').click();
      await modal.waitFor({ state: 'visible', timeout: 2000 });
      assert.equal(await modal.locator('input[name=token]').inputValue(), 'page-camera-12');
      await modal.locator('input[name=name]').fill('Page Camera Sub');
      await modal.locator('input[name=url_device]').fill('rtsp://sub');
      await modal.locator('input[name=ip_access]').fill('127.0.0.1');
      await save();
      await page.locator('#home_tab_6').click();
      await lastPage();
      row = page.locator('tr').filter({ hasText: 'Page Camera Sub' });
      await row.locator('.edit_data').click();
      await modal.waitFor({ state: 'visible', timeout: 2000 });
      await modal.locator('input[name=name]').fill('Edited Camera Sub');
      await save();
      await page.locator('#home_tab_6').click();
      await lastPage();
      await confirmDelete(page.locator('tr').filter({ hasText: 'Edited Camera Sub' }));
      await lastPage();
      assert.equal(await page.locator('tr').filter({ hasText: 'Edited Camera Sub' }).count(), 0);
      console.log('PASS later-page vendor/user Edit/Delete and CCTV Create Sub/Edit/Delete');
    }
  } finally {
    await browser.close();
  }
})().catch(error => { console.error(error); process.exit(1); });
