\! echo "--- INSERTING EXAMPLE DATA ---"

\! echo "Adding User List"
INSERT INTO oauthserver.users
    (user_name, user_email)
    VALUES
    ('Jim', 'jim@example.com'),
    ('Dave', 'davegthemighty@hotmail.com')
    ;
