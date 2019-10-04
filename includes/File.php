<?php
class File
{
    static function upload($file, $location = "", $file_size = null, $file_type = [])
    {
        $name = $file["name"];
        $type = $file["type"];
        $size = $file["size"];
        $temp = $file["tmp_name"];
        $error = $file["error"];

        if ($error > 0) {
            die(json_encode(['message' => "Error uploading file! code $error."]));
            return false;
        }
        if (count($file_type) != 0) {
            $fileType = explode("/", mime_content_type($temp))[0];
            $mineType = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            foreach ($file_type as $key => $value) {
                if ($key == $fileType) {
                    foreach ($value as $ext) {
                        if (strtolower($ext) == $mineType) {
                            $error = false;
                            break;
                        }
                        $supported = json_encode($value);
                        $error = true;
                    }
                    break;
                }
                $supported = json_encode([$value]);
                $error = true;
            }
            if ($error) {
                die(json_encode(['message' => "$type Not supported, supported is $supported"]));
                return false;
            }
        }



        if (!empty($file_size)) {
            if ($size > $file_size) {
                die(json_encode(['message' => "Invalid file size, File must not exceed $file_size"]));
                return false;
            }
        }

        if (strpos($location, '.') !== false) {
            $file_location = storage_path() . substr($location, 0, strrpos($location, '/'));
        } elseif (strrpos($location, '/') !== false) {
            $file_location = storage_path() . substr($location, 0, strrpos($location, '/'));
        } else {
            $file_location = storage_path() . 'public';
        }
        $file_name = substr(strrchr($location, '/'), 1);

        if (strripos($file_name, '.') !== false) {
            $real_name = $file_name;
        } else {
            $real_name = file_name($file);
            $file_location = storage_path() . $location;
        }
        // die(json_encode(['message' => $file_location]));

        if (!file_exists($file_location)) {
            mkdir($file_location, 0777, true);
        }

        if (move_uploaded_file($temp, $file_location . '/' . $real_name)) {
            return str_replace(storage_path(), '', $file_location . '/' . $real_name);
        }
    }
}

function storage_path()
{
    return  $_SERVER['DOCUMENT_ROOT'] . '/storage/';
}

function extension($file)
{
    $name = $file["name"];

    return pathinfo($name, PATHINFO_EXTENSION);
}
function file_name($file)
{
    return $file['name'];
}
