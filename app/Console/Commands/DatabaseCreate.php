<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 27/03/19
 * Time: 14:20
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;
use PDOException;

class DatabaseCreate extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:create';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a New DB ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $database = env('DB_DATABASE');

        if (! $database) {
            $this->info('Skipping creation of database as env(DB_DATABASE) is empty');
            return;
        }

        try {
            $user = env('DB_USERNAME');
            $pass = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $dbType = env('DB_CONNECTION');

            $pdo = $this->getPDOConnection($dbType, $host, env('DB_PORT'), $user, $pass);

            if ($dbType == 'mysql') {
                $pdo->exec("CREATE DATABASE " .$database. ";
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                GRANT ALL ON '$database'.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;");
            } else {
                $pdo->exec(
                    "CREATE DATABASE $database
                    WITH OWNER $user;"
                );
            }
            $this->info(sprintf('Successfully created %s database', $database));
        } catch (\Exception $exception) {
            $this->error(sprintf('Failed to create %s database, %s', $database, $exception->getMessage()));
        }
    }

    /**
     * @param  string $host
     * @param  integer $port
     * @param  string $username
     * @param  string $password
     * @return PDO
     */
    private function getPDOConnection($driver, $host, $port, $username, $password)
    {
        return new PDO(sprintf('%s:host=%s;port=%d;', $driver, $host, $port), $username, $password);
    }
}