# webshop

This is a simple webshop that we use to practice web security.

## Files:
* css/style.css </br>
* class/db.php The database class. Use this for interacting with the db. </br>
* inc/footer.php The footer </br>
* inc/header.php The header </br>
* basket.php Shows the items, and how many of them the user has put in the basket </br>
* config.sql The code that we use to initialize the db.
* config.php Contains login information to db.  </br>
* functions.php Functions for authenticating, validate email and more </br>
* index.php Startpage, the user can log in, create an acount, check the basket </br>
* items.php Shows the items that can be bought in the shop </br>
* login.php The user fills in username and password </br>
* new-user.php The user create an acount, with username,password and home address </br>

Link to google doc: https://docs.google.com/a/student.lu.se/document/d/1O-rdShlBjOP3qO6Z3Dhh71Zc8gab6ct_VAjz9UeFsD0/edit?usp=sharing

Link to presentation: https://docs.google.com/a/student.lu.se/presentation/d/1iXJPWHQCoou_reIiRkmjzPmFXAriBoRYPBRdn6KebfE/edit?usp=sharing

## Changes to config files when in production.
Note that the primary usage for some *AMP stacks (such as XAMPP, MAMP, WAMP, etc.) is to easily deploy a friendly developer environment. So they are not (out of the box) secure for production use.

### Attached config files.
The configurations for WAMP is attached in the folder configfiles with instructions for how they should be set up, in case of a SSL connection using HTTPS.
Below some of the changes for php.ini can be read. 

### Hide server information
Security through obscurity -> theoretically bad idea, ok in practice.
The fact that you use PHP and which version is sent in HTTP header. To make it harder for an attacker ta attack the site this information can be hidden.

To hide php information (in php.ini): 
```
    expose_php=On|Off
```
Set to **Off**.
### Register globals
If register_globals option is on then global variables can be set through GET or POST.
This can be a security risk if programming is bad.
Standard since PHP 4.2.0 is off. In php.ini:
```
    register_globals = On|Off
```
Set to **Off**.
### Error reporting
When developing the application it is advantageous to display all errors to screen. But in production, this can be a security risk.
```
    display_errors = On|Off
```
Set to **Off**.
And then store errors in logs instead (php.ini):
```
    log_errors = On
    error_log = "/Applications/MAMP/logs/php_error.log"
```
Set log_errors to **On**.
Note that error_log path differs between servers.
### Remote file inclusion
To protect against *Mallory* running a malicious script on the server, disable allow_url_fopen.
In php.ini:

```
    allow_url_fopen = On|Off
    
```
Set to **Off**.
### Restrict directory access
The default setting is to allow all files to be opened. By specifying a path with [open_basedir](http://php.net/manual/en/ini.core.php#ini.open-basedir) then only those directories can be opened.
In php.ini:

```
    open_basedir = path/to/webserver
```
### Restrict cookie access to only html
Marks the cookie as accessible only through the HTTP protocol. This means that the cookie won't be accessible by scripting languages, such as JavaScript. This setting can effectively help to reduce identity theft through XSS attacks (although it is not supported by all browsers). http://php.net/manual/en/session.configuration.php#ini.session.cookie-httponly
Add in php.ini:

```
session.cookie_httponly = 1
```

### Restrict session to only use cookies
From the comment inside php.ini:
This option enables administrators to make their users invulnerable to
attacks which involve passing session ids in URLs; defaults to 0.
Change in php.ini:

```
session.use_only_cookies = 1
```

### Add additional entropy source in the session id creation process
From the php documentation:
session.entropy_file gives a path to an external resource (file) which will be used as an additional entropy source in the session id creation process. Examples are /dev/random or /dev/urandom which are available on many Unix systems. This feature is supported on Windows since PHP 5.3.3. Setting session.entropy_length to a non zero value will make PHP use the Windows Random API as entropy source.
Note: As of PHP 5.4.0 session.entropy_file defaults to /dev/urandom or /dev/arandom if it is available. In PHP 5.3.0 this directive is left empty by default.

Comment out in php.ini:
```
;session.entropy_file =
```

Uncomment in php.ini:

```
session.entropy_file = /dev/urandom
```

### Hide database password
The password should not be visible in the php file. Instead this should be kept in another file that isn't as excessable to the application.

#### PDO
Add these to the php.ini:

```
cfg.DB_USER = 'root'
cfg.DB_NAME = 'javacookie_db'
cfg.DB_PASS = ''
```
And these variables can be read and assigned with the following code (already added):

```
// Very simple loader
function loadConfig( $vars = array() ) {
    foreach( $vars as $v ) {
        define( $v, get_cfg_var( "cfg.$v" ) );
    }
}
 
// Then call :
$cfg = array( 'DB_HOST', 'DB_USER', 'DB_PASS' );
loadConfig( $cfg );
```
