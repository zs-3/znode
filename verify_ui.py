from playwright.sync_api import sync_playwright

def verify_pages():
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()

        # 1. Verify Landing Page
        page.goto("http://localhost:8080/php-version/landing.php")
        page.screenshot(path="verification_landing.png")
        print("Landing page screenshot taken.")

        # 2. Verify Login Page
        page.goto("http://localhost:8080/php-version/login.php")
        page.screenshot(path="verification_login.png")
        print("Login page screenshot taken.")

        # 3. Verify Install Page
        page.goto("http://localhost:8080/php-version/install.php")
        page.screenshot(path="verification_install.png")
        print("Install page screenshot taken.")

        browser.close()

if __name__ == "__main__":
    verify_pages()
