# Deploying afriJudith.online

Target: `westcoa2@<your-host>:/home3/westcoa2/afrijudith.online`
Repo:   https://github.com/androidnega/AfriJudith

There are **three** ways to push the code to the server. Pick whichever you
have tools for. **Option A (git pull on the server)** is the cleanest going
forward — every future change is one command away.

---

## Option A — Pull from GitHub on the server (recommended)

You only have to do this **once**, then every update is just a `git pull`.

1. Open PuTTY and SSH into the server as `westcoa2`.
2. Run:

   ```bash
   cd /home3/westcoa2
   # Move the current folder out of the way as a safety backup.
   mv afrijudith.online afrijudith.online.backup-$(date +%Y%m%d-%H%M%S) 2>/dev/null || true

   # Clone the repo INTO that exact folder name.
   git clone https://github.com/androidnega/AfriJudith.git afrijudith.online

   cd afrijudith.online

   # Make sure Apache can read everything and write nothing it shouldn't.
   find . -type d -exec chmod 755 {} \;
   find . -type f -exec chmod 644 {} \;
   ```

3. For every future deploy:

   ```bash
   cd /home3/westcoa2/afrijudith.online
   git pull origin main
   ```

   That's it.

---

## Option B — PSCP / WinSCP from your Windows machine

PuTTY itself only opens an SSH terminal; the file-copy tool that ships with
it is **PSCP** (and **WinSCP** for a GUI). Either works.

### B.1 PSCP (command line, Windows)

From the folder where you have the project (or after `git clone` locally):

```bat
pscp -r * westcoa2@your-host:/home3/westcoa2/afrijudith.online/
pscp .htaccess westcoa2@your-host:/home3/westcoa2/afrijudith.online/
```

> `*` doesn't include dotfiles on Windows shells, so push `.htaccess`
> explicitly with the second line.

### B.2 WinSCP (drag-and-drop GUI)

1. Open WinSCP, connect with the same credentials PuTTY uses.
2. Navigate to `/home3/westcoa2/afrijudith.online/` on the right pane.
3. Drag the **entire** project (including `.htaccess` and `.gitignore`) over.
4. When prompted about overwriting, choose **Yes to all**.

---

## Option C — rsync from macOS / Linux

If you ever switch from PuTTY to a Mac/Linux box, this is the fastest:

```bash
rsync -avz --delete \
    --exclude='.git' \
    --exclude='.DS_Store' \
    --exclude='tools/' \
    ./ westcoa2@your-host:/home3/westcoa2/afrijudith.online/
```

---

## After uploading — one-time checks on the server

```bash
cd /home3/westcoa2/afrijudith.online

# Confirm key files are present
ls -la .htaccess index.php
ls app/controllers/ app/models/ app/views/

# Apache must honor .htaccess. cPanel usually has it on by default —
# if rewrites don't work, ask your host to enable AllowOverride All.
```

Open the site:

- https://afrijudith.online/         → 100vh landing page
- https://afrijudith.online/about    → About
- https://afrijudith.online/skills   → Skills
- https://afrijudith.online/work     → Work
- https://afrijudith.online/contact  → Contact form

If `/about` shows a 404 but `/index.php?url=about` works, your host needs
`AllowOverride All` for the docroot — open a cPanel ticket and ask for it.

---

## Contact form — sending mail

The form posts to `/contact`, which is handled by `ContactController` and
forwarded by `app/models/MailerModel.php` using PHP's built-in `mail()`.

- **To:**   `hello@afrijudith.online`
- **From:** `no-reply@afrijudith.online`
- **Reply-To:** the visitor's email, so you can hit Reply in your inbox.

### One-time mail setup on the server

1. In cPanel → **Email Accounts**, create the inbox `hello@afrijudith.online`
   (or set it as a forwarder to whatever you already check).
2. In cPanel → **Email Deliverability** for `afrijudith.online`,
   click **Repair** and make sure SPF and DKIM are green. Without this,
   Gmail/Outlook will drop messages even though `mail()` "succeeded".
3. Test from the live site by submitting the contact form once.
4. If nothing arrives, check `~/mail/afrijudith.online/hello/` and the
   server's mail log (cPanel → **Track Delivery**).

> If shared hosting throttles `mail()`, swap `MailerModel::sendContact()`
> to use SMTP (PHPMailer) — only that one method needs to change.

---

## URLs without `.php`

Already handled by `.htaccess`:

- `https://afrijudith.online/contact.php` → 301-redirects to `/contact`
- `https://afrijudith.online/index.php`   → 301-redirects to `/`
- `https://afrijudith.online/about`       → served by `index.php` (front controller)

The MVC routes (`/about`, `/skills`, etc.) never expose `.php` in the first
place because everything goes through the front controller.

---

## Folder & file permissions cheat-sheet (cPanel default)

| Type        | Mode |
|-------------|------|
| Directories | 755  |
| PHP files   | 644  |
| Assets      | 644  |
| `.htaccess` | 644  |

If you ever see a 500 error after upload, the most common cause is a file
uploaded as 777 — cPanel will refuse to execute it. Fix with:

```bash
cd /home3/westcoa2/afrijudith.online
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

---

## Updating later

Once Option A is in place, you push from your laptop and pull on the server:

```bash
# laptop
git add -A && git commit -m "Tweak: …" && git push origin main

# server (via PuTTY)
cd /home3/westcoa2/afrijudith.online && git pull origin main
```
