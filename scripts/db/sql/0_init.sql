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
  homepage_url VARCHAR(255) NOT NULL UNIQUE,
  application_description TEXT NULL,
  callback_url VARCHAR(255) NOT NULL,
  client_id VARCHAR(32) NOT NULL UNIQUE,
  client_secret VARCHAR(32) NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL
);

select * from oauthserver.applications;
\! echo "Done!"

\! echo "Creating Authorization Table..."
CREATE TABLE oauthserver.authorizations (
	authorization_id  SERIAL PRIMARY KEY,
  client_id VARCHAR(32) NOT NULL REFERENCES oauthserver.applications (client_id),
  scope VARCHAR(255) NOT NULL,
  state VARCHAR(255) NOT NULL,
  auth_code VARCHAR(32) NOT NULL,
  auth_code_expiration timestamp NOT NULL,
  access_token VARCHAR(32) NOT NULL,
  token_expiration timestamp NOT NULL,
	created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL
);

select * from oauthserver.authorizations;
\! echo "Done!"

\! echo "Granting Schema Privs..."
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA oauthserver TO oauthserver;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA oauthserver TO oauthserver;
