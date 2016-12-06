<?php
/**
 * Интерфейс необходимый моделям для импорта данных из legacy базы.
 */

namespace app\models;

interface iLegacyImport
{
    /**
     * @param array $data данные для записи
     *
     * @return string возвращает массив ошибок
     */
    public function loadData($data);
}
