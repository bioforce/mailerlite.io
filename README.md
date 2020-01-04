## MailerLite Subscribers

# How to run:
git pull
composer install
Edit .env file for database connection
php artisan migrate
And open: /subscribe - page

# Models
2 Models: Suscriber and Fields
Subscriber has many fields with pivot: value

# Validates
email and DomainValidation

# Admin pages
/admin/subscribers - Subscribers
/admin/fields - Additional fields

# Subscribe form
/subscribe

# Test
HomeControllerTest - admin pages with authorized user and without
SubscriberControllerTest - list of subscribers, add subsriber from adimn, subscribe page, subscribe page without email

# Api resourse
/api/subscribers
/api/fields
/api/subscribe - For subscribe user from front