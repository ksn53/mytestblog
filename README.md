# ver 0.35
# mytestblog

login: admin
password: 111


  technical task

Site structure
-------------------------------------------------- -------
Pages on the site:

     main (it is also a list of articles);
     blog article detail page;
     authorization;
     registration;
     Personal account of a registered user, profile and subscriptions;
     other static pages created using the CMS;
     CMS admin.

Roles on the site
-------------------------------------------------- -------
Site administrators should be able to grant registered users the roles of Administrator and Content Manager.

User roles:

Administrator - full access to the admin panel;
Content Manager – can edit/create articles and moderate comments on them;
Registered user - can leave comments;
Unregistered user - can view blog articles, subscribe, log in and register.

Page Composition
-------------------------------------------------- -------
The site header consists of the site name, the user menu and the menu for navigating the site.

The user menu contains links to registration authorization (if the user is not authorized) and links to the profile in the personal account (if he is authorized).

Main page (list of articles)
-------------------------------------------------- -------
The list of blog articles is displayed in descending order by creation date. For each article, the title, short description, picture and date of publication are displayed. The page displays a limited number of elements, which can be changed in the administrative interface. If there are more articles, pagination appears. The sign block with the email field (for an unauthorized user) and without this field (for an authorized user), if it has not yet been signed.

Article Detail Page

The title, picture, date of publication and the full content of the article are displayed. After the article, comments to it and a form for adding a new comment are displayed. Only an authorized user can leave a comment; if an unauthorized user tries to add a comment, an error message and an offer to log in should be shown. The added comment is displayed only to the author and moderators, with a note that the comment has not yet been approved. Moderators have the right to approve a comment or reject it. If a comment is added by the Content Manager or Administrator, then it is immediately marked as moderated and displayed to everyone. Each comment contains the profile picture and full name of the author, the date of publication and the comment itself.

Login Page
-------------------------------------------------- -------
An authorization form with email and password fields is displayed. There is also a registration link below the form. If there is an authorization error, this error should be displayed.

Registration Page

A registration form is displayed with the fields name, email, password and password confirmation, as well as with a checkmark “I agree with the rules of the site” and a link to these rules. All fields are mandatory. An authorization link is displayed below the form. If there is a registration error, this error should be displayed next to the field to which the error relates. After registration, the user is automatically authorized.


Profile Page
-------------------------------------------------- -------
The profile form is displayed. In addition to registration fields, the user can upload an avatar (only an image, no larger than 2 MB) and write the text “About me – these fields are optional. When a save error occurs, the errors should be displayed next to the field they refer to.

Also, the “Subscribe” or “Unsubscribe” block is displayed here if the user is already subscribed.

Static pages
-------------------------------------------------- -------
They are created in the admin panel, they display the content specified when creating the page.


Static page
with the terms of use of the site

A static page must be created that displays the rules for using the site.
Administrative section
-------------------------------------------------- -------
It contains an interface for managing users and their roles, an interface for managing articles, managing subscriptions, managing comments, and managing static pages, as well as a separate page with additional settings. At the same time, the administrative section should have a menu for quick navigation between subsections. Data lists are displayed on pages with pagination, the number of elements per page is set by the GET parameter. To change it, select is used with options 10, 20, 50, 200, all. Default: 20.

Users of the Content Manager level can enter the administrative interface and fully manage only sections of articles, comments and static pages. Access to other sections should be closed.

Subscription for updates
-------------------------------------------------- -------
Any user can subscribe his email to receive notifications about the appearance of a new article on the site. The “Subscribe” block contains an email field (for an unauthorized user) and a “Subscribe” button. When you click on the button, the specified email is added to the mailing list. For an authorized user, the email field is hidden and its email is automatically substituted.

If the user subscribed already, then the block not displayed.

You can cancel the subscription in the settings in the personal account, as well as using a specially generated link in the mailing list.

Newsletter
-------------------------------------------------- -------
When a new article added on the site, all users subscribed to the newsletter are sent (there is no need to do a real sending, the text of the letter is written to the log indicating the time of sending and the addressee) email notification. Notification content:

Subject: A new entry has been added to the site: “#Name of the new article#”

Message content:

New article: “#Name of new article#”,

#Brief description of the article#

Read (link to article page)

-------

Unsubscribe from the mailing list (link to unsubscribe from the mailing list)
-------------------------------------------------- --------------------------------------

The following components used: twig template engine, various Symfony components, Eloquent ORM, Bootstrap template, jquery.
Implemented architectural elements: MVC, router, widgets, mailer, admin panel, form validation.
Bootstrap and jquery elements actively used.
