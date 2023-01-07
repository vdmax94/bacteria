<?php
require_once dirname(__DIR__). '/Config/constants.php';
require_once BASE_DIR. '/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
$dotenv->load();

use Core\Db;
class Migration {
    const SCRIPTS_DIR = __DIR__.'/scripts/';
    const MIGRATIONS_TABLE = '0_migrations';
    public $migration = null;

    public function __construct($argument=false)
    {
        try{
            if ($argument){
                $this->migration = match($argument){
                    "checkmigration" => $this->checkMigrationTable(true),
                    "createmigration" => $this->createMigrationTable(true),
                    "runall" => $this->runAllMigration(true)
                };

            }else {
                //check migration table and create it
                if (!$this->checkMigrationTable()) {
                    $this->createMigrationTable();
                }

                //run all migration
                $this->runAllMigration();
            }
        }catch (PDOException $exception){
            dd($exception->getMessage());
        }
    }

    public function checkMigrationTable($cli = false){
        $query = Db::getConnect()->prepare("SHOW TABLES LIKE '".$this->getTableName(self::MIGRATIONS_TABLE)."'");
        $query->execute();
        $result = $query->fetch();

        if($cli && !$result){
            return "The migration table is absent! Create it by arguments 'createmigration'!";
        }elseif ($cli){
            return "The migration table is exist!";
        }
        return $result;
    }

    public function createMigrationTable(){
        d('---Prepare migration table query');
        $script = file_get_contents(self::SCRIPTS_DIR.self::MIGRATIONS_TABLE.'.sql');
        $query = Db::getConnect()->prepare($script);
        $result = $query->execute();
        if($result){
            $text = '#Migrations table was created!';
            unset($this->migration);
        }else{
            $text = "#Failed!((";
        }

        d($text);
        d('---Finished migration table query');
    }

    protected function runAllMigration($cli = false){
        d('---Fetching migration');
        $migration = scandir(self::SCRIPTS_DIR);
        $migration = array_values(array_diff($migration, ['.','..', self::MIGRATIONS_TABLE.'.sql']));
        $checkIfMigrationRun = false;

        foreach ($migration as $value) {
            $table = $this->getTableName($value);
            if (!$this->checkIfMigrationWasRun($value)){
                d("-Run [$table]...");
                $script = file_get_contents(self::SCRIPTS_DIR.$value);
                $query = Db::getConnect()->prepare($script);

                if($query->execute()){
                    $this->insertIntoMigrationTable($value);
                }
                $checkIfMigrationRun = true;
            }
        }
        if(!$checkIfMigrationRun){
            d('-Absent new SQL-query');
        }
        d('---Fetching migrations - done!');

        if($cli){
            unset($this->migration);
        }
    }

    protected function insertIntoMigrationTable(string $fileName){
        $query=Db::getConnect()->prepare("INSERT INTO migrations (name) VALUES (:name)");
        $query->bindParam('name', $fileName);
        $query->execute();
    }

    protected function checkIfMigrationWasRun(string $migration):bool
    {
        $query = Db::getConnect()->prepare('SELECT * FROM migrations WHERE name=:name');
        $query->bindParam('name',$migration);
        $query->execute();

        return (bool) $query->fetch();
    }

    protected function getTableName(string $fileName): string
    {
        return preg_replace('/[\d_+]/i', '', $fileName);
    }


}

if(PHP_SAPI == "cli"){
    unset($argv[0]);
    $arguments = array_values($argv);
    if(!$arguments){
        $migration = new Migration();
    }else {
        foreach ($arguments as $value) {
            $migration = match ($value) {
                'migration' => new Migration(),
                'checkmigration' => new Migration('checkmigration'),
                'createmigration' => new Migration('createmigration'),
                'runall' => new Migration('runall'),
                default => "Unknown argument '$value'"
            };

            if (isset($migration->migration)) {
                d($migration->migration);
            }elseif(!is_object($migration)){
                d($migration);
            }
        }
    }
}