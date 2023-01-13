<?php
namespace Core\Traits;

use Core\Db;
use PDO;
trait Queryable
{
    protected static string|null $tableName = '';
    protected static string $query = '';

    protected array $commands = [];

    public static function select (array $columns = ['*']):static
    {
        static::resetQuery();
        static::$query = 'SELECT '.implode(', ', $columns). ' FROM '.static::$tableName. " ";
        $obj = new static();
        $obj->commands[] = 'select';

        return $obj;
    }

    //INSERT into () VALUES ();
    public static function create(array $data){
        $params = static::prepareQueryVars($data);
        $query = 'INSERT INTO '. static::$tableName.' ('.$params['keys'].') VALUES ('.$params['placeHolders'].')';
        $query = Db::getConnect()->prepare($query);
        $query->execute($data);

        return (int) Db::getConnect()->lastInsertId();
    }

    public static function selectAll ():static
    {
        static::resetQuery();
        static::$query = "SELECT * FROM ".static::$tableName;

        $obj = new static();
        $obj->commands[] = 'selectAll';

        return $obj;
    }

    protected static function prepareQueryVars(array $fields): array
    {
        $keys = array_keys($fields);
        $placeHolders = preg_filter('/^/', ':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeHolders' => implode(', ', $placeHolders)
        ];
    }

    protected static function resetQuery(){
        static::$query = '';
    }

    public function get()
    {
        return Db::getConnect()->query(static::$query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function where(string $columns, string $operator, $value): static
    {
        if(!$this->can(['select'])){
            throw new \Exception('SELECT should be called before WHERE');
        }

        static::$query .= "WHERE {$columns} {$operator} {$value}";

        $obj = new static();
        $obj->commands[] = 'where';

        return $obj;
    }

    protected function can($allowedMethod){
        foreach ($allowedMethod as $method) {
            if (in_array($method, $this->commands)) {
                return true;
            }
        }
        return false;
    }

}