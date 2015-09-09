<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 18/07/15
 * Time: 23.28
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace it\thecsea\users_management;
use it\thecsea\mysqltcs\Mysqltcs;
use it\thecsea\mysqltcs\MysqltcsException;
use it\thecsea\mysqltcs\MysqltcsOperations;


/**
 * Class UsersManagement
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 Claudio Cardinale
 * @version 1.0.0
 * @package it\thecsea\users_management
 */
class UsersManagement
{
    /**
     * @var Mysqltcs
     */
    private $connection;

    /**
     * @var String
     */
    private $usersTable;

    /**
     * @param Mysqltcs $connection a valid and connected instance of Mysqltcs
     * @param String $usersTable users table name
     * @throws UsersManagementException on connection errors
     */
    public function __construct(Mysqltcs $connection, $usersTable)
    {
        $this->connection = $connection;
        $this->usersTable = $usersTable;

        self::connectionCheck($connection);
        self::usersTableCheck($connection, $usersTable);
    }

    /**
     * throw exception if mysqltcs passed is not valid
     * @param Mysqltcs $connection
     * @throws UsersManagementException
     */
    private static function connectionCheck(Mysqltcs $connection)
    {
        if($connection == null || !($connection instanceof Mysqltcs)) {
            throw new UsersManagementException("Connection passed is not an instance of Mysqltcs");
        }

        if(!$connection->isConnected()) {
            throw new UsersManagementException("Connection passed is not connected");
        }
    }


    /**
     * throw exception if usersTable passed is not valid
     * @param Mysqltcs $connection
     * @param string $usersTable
     * @throws UsersManagementException
     */
    private static function usersTableCheck(Mysqltcs $connection, $usersTable)
    {
        //check table name
        try{
            $operations = new MysqltcsOperations($connection, $usersTable);
            if($operations->getTableInfo("Name") != $usersTable) {
                throw new UsersManagementException("Table name passed is not corrected");
            }
        }catch(MysqltcsException $e){
            throw new UsersManagementException("Table name passed is not corrected",0, $e);
        }
    }


    /**
     * This entails that you can clone every instance of this class
     */
    public function __clone()
    {
    }

    /**
     * @return string
     */
    function __toString()
    {
        return ("users table: ".$this->usersTable."\nmysqltcs:\n" . (string)$this->connection);
    }

    /**
     * @return Mysqltcs
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return String
     */
    public function getUsersTable()
    {
        return $this->usersTable;
    }

    /**
     * @param Mysqltcs $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        self::connectionCheck($connection);
    }

    /**
     * @param String $usersTable
     */
    public function setUsersTable($usersTable)
    {
        $this->usersTable = $usersTable;
        self::usersTableCheck($this->connection, $usersTable);
    }

}