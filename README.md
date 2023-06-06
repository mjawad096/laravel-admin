# Installation

>`composer require mjawad096/laravel-admin`

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
 ┃ ┣ 📜confirm
 ┃ ┣ 📜email
 ┃ ┗ 📜reset
 ┣ 📜login
 ┣ 📜register
 ┗ 📜verify
 📂components
 ┣ 📜auth-session-status
 ┗ 📜auth-validation-errors
 📂crud
 ┣ 📜details
 ┣ 📜form
 ┗ 📜list
 📂fields
 ┣ 📜date
 ┣ 📜editor
 ┣ 📜field
 ┣ 📜file
 ┣ 📜image
 ┣ 📜select
 ┣ 📜status
 ┣ 📜text
 ┗ 📜textarea
 📂inc
 ┣ 📜datatables-config
 ┣ 📜footer
 ┣ 📜header
 ┣ 📜sidebar-menu
 ┣ 📜sidebar
 ┗ 📜user-menu
 📂layouts
 ┣ 📜app
 ┗ 📜main
 📜settings
```
