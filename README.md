# Installation

>`composer require jd-dotlogics/laravel-admin`

Installation
>`php artisan laravel-admin:install`


Create Default User
>`php artisan laravel-admin:default-admin`

Publish and override views (This will publish the fields/text.blade.php)
>`php artisan laravel-admin:publish fields/text`


Here is the list of all publishable views

```
 📂auth
 ┣ 📂passwords
 ┃ ┣ 📜confirm.blade.php
 ┃ ┣ 📜email.blade.php
 ┃ ┗ 📜reset.blade.php
 ┣ 📜login.blade.php
 ┣ 📜register.blade.php
 ┗ 📜verify.blade.php
 📂components
 ┣ 📜auth-session-status.blade.php
 ┗ 📜auth-validation-errors.blade.php
 📂crud
 ┣ 📜details.blade.php
 ┣ 📜form.blade.php
 ┗ 📜list.blade.php
 📂fields
 ┣ 📜date.blade.php
 ┣ 📜editor.blade.php
 ┣ 📜field.blade.php
 ┣ 📜file.blade.php
 ┣ 📜image.blade.php
 ┣ 📜select.blade.php
 ┣ 📜status.blade.php
 ┣ 📜text.blade.php
 ┗ 📜textarea.blade.php
 📂inc
 ┣ 📜datatables-config.blade.php
 ┣ 📜footer.blade.php
 ┣ 📜header.blade.php
 ┣ 📜sidebar-menu.blade.php
 ┣ 📜sidebar.blade.php
 ┗ 📜user-menu.blade.php
 📂layouts
 ┣ 📜app.blade.php
 ┗ 📜main.blade.php
 📜settings.blade.php
```