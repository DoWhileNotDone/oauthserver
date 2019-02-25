\! echo "--- INIT SCRIPT ---"

DROP SCHEMA IF EXISTS oauthserver CASCADE;

DO
$do$
BEGIN
   IF NOT EXISTS (
      SELECT                       -- SELECT list can stay empty for this
      FROM   pg_catalog.pg_roles
      WHERE  rolname = 'oauthserver') THEN
      CREATE USER oauthserver WITH ENCRYPTED PASSWORD 'oauthserver';
   END IF;
END
$do$;

CREATE SCHEMA oauthserver AUTHORIZATION oauthserver;

\! echo "Creating Tables..."

\! echo "Creating Application Table..."
CREATE TABLE oauthserver.applications (
	application_id  SERIAL PRIMARY KEY,
	application_name VARCHAR(255) NOT NULL UNIQUE,
  homepage_uri VARCHAR(255) NOT NULL UNIQUE,
  application_description TEXT NULL,
  callback_uri VARCHAR(255) NOT NULL,
  client_id VARCHAR(70) NOT NULL UNIQUE,
  client_secret VARCHAR(70) NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL
);

select * from oauthserver.applications;
\! echo "Done!"

\! echo "Creating User Table..."
CREATE TABLE oauthserver.users (
	user_id  SERIAL PRIMARY KEY,
	user_name VARCHAR(255) NOT NULL,
  user_email VARCHAR(255) NOT NULL UNIQUE,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL
);

select * from oauthserver.users;
\! echo "Done!"

\! echo "Creating Authorization Table..."
CREATE TABLE oauthserver.authorizations (
	authorization_id  SERIAL PRIMARY KEY,
  application_id INTEGER NOT NULL REFERENCES oauthserver.applications (application_id),
  user_id INTEGER NOT NULL REFERENCES oauthserver.users (user_id),
  scope VARCHAR(255) NOT NULL,
  state VARCHAR(255) NOT NULL,
  auth_code VARCHAR(70) NOT NULL,
  auth_code_expiration timestamp NOT NULL,
  access_token VARCHAR(70) NULL,
  token_expiration timestamp NULL,

	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL
);

select * from oauthserver.authorizations;
\! echo "Done!"

\! echo "Granting Schema Privs..."
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA oauthserver TO oauthserver;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA oauthserver TO oauthserver;
