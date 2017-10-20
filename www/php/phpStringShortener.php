<?php
/**
 * PHPStringShortener
 * 
 * A simple string shortener class to demonstrate how to shorten strings, such
 * as URLs using Base62 encoding.
 * 
 * @author Matthias Kerstner <matthias@kerstner.at>
 * @uses PDO 
 * @link https://www.kerstner.at/phpStringShortener
 */
require ("../scripts/conn.php");
 
class PhpStringShortener {
 
    /**
     * @var PDO
     */
    private $DB_HANDLE = null;
 
    /**
     * The Base62 alphabet to be used.
     * @var array
     */
    private $BASE62_ALPHABET = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-');
 
    //potential additional characters allowed unencoded in URLs include:
    //'$', '-', '_', '.', '+', '!', '*', '(', ')', ','
    //@see http://www.faqs.org/rfcs/rfc1738.html
 
    /**
     * Returns DB handle, implemented as singleton.
     * @return PDO
     * @see config.php
     */
    private function getDbHandle() {
 
        if ($this->DB_HANDLE !== null) {
            return $this->DB_HANDLE;
        }
 
        try {
            $this->DB_HANDLE = new PDO('mysql:host='
                            . PHPSS_DBHOST . ';dbname='
                            . PHPSS_DBDB, PHPSS_DBUSER, PHPSS_DBPASS, array(
                        PDO::ATTR_PERSISTENT => true
                    ));
            $this->DB_HANDLE->exec("SET CHARACTER SET utf8");
 
            return $this->DB_HANDLE;
        } catch (PDOException $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
 
    /**
     * Closes the DB handle. 
     */
    private function closeDbHandle() {
        if ($this->DB_HANDLE !== null)
            $this->DB_HANDLE = null;
    }
 
    /**
     * Generates hash for $id specified using Base62 encoding.
     * @param int $id
     * @return string|null
     */
    private function generateHashForId($id) {
        $hash = '';
        $hashDigits = array();
        $dividend = (int) $id;
        $remainder = 0;
 
        while ($dividend > 0) {
            $remainder = floor($dividend % 62);
            $dividend = floor($dividend / 62);
            array_unshift($hashDigits, $remainder);
        }
 
        foreach ($hashDigits as $v) {
            $hash .= $this->BASE62_ALPHABET[$v];
        }
 
        return $hash;
    }
 
    /**
     * Closes DB handle.
     */
    public function __destruct() {
        $this->closeDbHandle();
    }
 
    /**
     * Returns string identified by $hash.
     * @param string $hash
     * @return string|null 
     */
    public function getStringByHash($hash) {
        $dbHandle = $this->getDbHandle();
        $sql = 'SELECT id, hash, url FROM phpss WHERE hash = BINARY :hash';
        $sth = $dbHandle->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':hash' => $hash));
        $entry = $sth->fetch(PDO::FETCH_ASSOC);
 
        if (!$entry || count($entry) < 1 || !isset($entry['url'])) {
            return null;
        }
 
        return $entry['url'];
    }
 
    /**
     * Returns hash identified by $string.
     * @param string $string
     * @return string|null 
     */
    public function getHashByString($string) {
        $dbHandle = $this->getDbHandle();
        $sql = 'SELECT hash, url FROM phpss WHERE url = :url';
        $sth = $dbHandle->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':url' => $string));
        $entry = $sth->fetch(PDO::FETCH_ASSOC);
 
        if (count($entry) > 0 && isset($entry['hash'])) { //hash already exists
            return $entry['hash'];
        }
 
        return null;
    }
 
    /**
     * Adds hash identified by $string if it does not already exist.
     * @param string $string 
     * @return string
     */
    public function addHashByString($string) {
        $hash = $this->getHashByString($string);
 
        if ($hash !== null) { //hash already exists
            return $hash;
        }
 
        $dbHandle = $this->getDbHandle();
        $sql = 'insert into phpss (id, hash, url) values(0, :hash, :url)';
        $sth = $dbHandle->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if (!$sth->execute(array(':hash' => '', ':url' => $string))) {
            throw new Exception('Error: failed to add entry (1)');
        }
 
        $lastInsertId = $dbHandle->lastInsertId();
 
        if (!$lastInsertId) {
            throw new Exception('Error: failed to add entry (2)');
        }
 
        $hash = $this->generateHashForId($lastInsertId);
 
        $sql = 'update phpss set hash = :hash where id = :id';
        $sth = $dbHandle->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if (!$sth->execute(array(':hash' => $hash, ':id' => $lastInsertId))) {
            throw new Exception('Error: failed to add entry (3)');
        }
 
        if ($hash !== null) { //hash already exists
            return $hash;
        }
    }
 
}
?>