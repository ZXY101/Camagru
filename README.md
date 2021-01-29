# Camagru

A simple web application based off of instagram, using the several core fundamentals of web development. 
Using PHP, HTML, CSS, JSS and SQL to create a website that allows you to register a new user and upload images to the site.

## Requiremtns
- XAMPP/MAMP

## Setup
- Clone the repository
- Start the webserver and DB in XAMP/MAMP
- Navigate to `localhost/Camagru` in your browser
- Setup the database by navigating to `http://localhost/Camagru/config/setup.php`

## Test Cases
| Test     | Expected Outcome |
| ----------- | ----------- |
| Attempt to navigate to a page you shouldn't have access to via the url bar | You recieve a 404 Error or are redirected to the login screen |
| Click the Register button and attempt to sign up with invalid information | You can not create an account until providing valid information |
| Provide valid input and register | You register successfully and recieve an email to validate your account |
| Attempt to sign in before validating your email | You cannot sign in |
| Verify your email and attempt to sign in again | You sign in |
| Visit your profile page and edit your information | Your information is updated |
| Signout and attempt to singin with your old ifnormation | You cannot sign in |
| Sign in with your new information | You sign in |
| Upload an image from your computer | It uploads |
| Upload an image via the webcam | It uploads |
| Attempt to upload a non image file | It does not upload |
| Navigate to a post you've created and like it multiple times | You can only like a post once and liking it again removes the like |
| Attempt an sql injection attack in the post's comment input | The comment is posted normally as plain text |
| Attempt a cross site scripting attack in the post's comment input | The comment is posted normally as plain text |
| Delete a post youve created | It is deleted |
| Logout and try to like/delet/comment on a post | You cannot |
