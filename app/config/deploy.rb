set :application, "Todo"
set :domain,      "todo.sameddin.pro"
set :deploy_to,   "/srv/#{domain}"
set :app_path,    "app"

set :repository,  "git@github.com:sameddin/todo-symfony.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

set :user, "root"
set :use_sudo, false
set :use_composer, true
set :copy_vendors, true

set :shared_files, ["app/config/parameters.yml"]

set :writable_dirs, ["app/cache", "app/logs"]
set :webserver_user, "www-data"
set :permission_method, :acl
set :use_set_permissions, true
set :dump_assetic_assets, true

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

namespace :php_fpm do
  task :restart do
    capifony_pretty_print "--> Restarting PHP-FPM"
    run "service php5-fpm restart"
    capifony_puts_ok
  end
end

namespace :bower do
  task :install do
    capifony_pretty_print "--> Run bower install"
    run "sh -c 'cd #{latest_release} && bower install --allow-root'"
    capifony_puts_ok
  end
end

before "symfony:assetic:dump", "bower:install"
after "deploy:finalize_update", "php_fpm:restart"
