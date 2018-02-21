<?php
/**
 * Class GoogleUploadHelper.
 *
 * Make upload and download to/from Google Drive
 *
 * See here: https://github.com/nao-pon/flysystem-google-drive/blob/master/example/GoogleUpload.php#L5
 *
 * Create API keys and setup Laravel: https://github.com/ivanvermeyen/laravel-google-drive-demo/blob/master/README.md#create-your-google-drive-api-keys
 *
 * Working with files & folders:  https://developers.google.com/drive/v3/web/folder
 * Download files: https://developers.google.com/drive/v3/web/manage-downloads
 * Search files: https://developers.google.com/drive/v3/web/search-parameters
 *
 *
 * Alternative implementation: https://github.com/yannisg/Google-Drive-Uploader-PHP/blob/master/gdrive_upload.php
 *
 * @author: Ekojoka Christolight Idakwo <chris.idakwo@gmail.com>
 * @date: 29/12/2017
 * @time: 00:06
 * @package: App\Helpers;
 */

namespace App\Helpers;

use Cache;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Storage;

class GoogleUploadHelper {
    protected $client;
    protected $folder_id;
    protected $service;
    protected $ClientId = "xxx";
    protected $ClientSecret = "xxx";
    protected $refreshToken = "xxx";

    public function __construct() {
        $this->ClientId = env("GOOGLE_DRIVE_CLIENT_ID");
        $this->ClientSecret = env("GOOGLE_DRIVE_CLIENT_SECRET");
        $this->refreshToken = env("GOOGLE_DRIVE_REFRESH_TOKEN");

        $this->client = new Google_Client();
        $this->client->setClientId($this->ClientId);
        $this->client->setClientSecret($this->ClientSecret);
        $this->client->refreshToken($this->refreshToken);

        $this->service = new Google_Service_Drive($this->client);

        // we cache the id to avoid having google creating
        // a new folder on each time we call it,
        // because google drive works with 'id' not 'name'
        // & thats why u could have duplicated folders under the same name
        Cache::rememberForever('folder_id', function () {
            return $this->create_folder();
        });

        $this->folder_id = Cache::get("folder_id");
    }


    protected function create_folder() {
        $fileMetadata = new \Google_Service_Drive_DriveFile([
            "name" => str_replace(" ", "-", env("APP_NAME") . "-V2"),
            "mimeType" => "application/vnd.google-apps.folder"
        ]);

        $folder = $this->service->files->create($fileMetadata, ["fields" => "id"]);

        return $folder->id;
    }

    /**
     * @param $fileId
     * @return Google_Service_Drive_DriveFile
     */
    public function get_file($fileId) {
        $response = $this->service->files->get($fileId, ["alt"=>"media"]);
        return $response;
    }

    /**
     * remove duplicated files before adding new ones
     * again thats because google use 'id' not 'name'.
     *
     * note that we cant search for files as bulk "a limitation in the drive Api it self"
     * so instead we call this method from a loop with all the files we want to remove
     *
     * also note that some of the api methods are from the v2 even if we are using v3 in this example
     * google docs are often mis-guiding
     * https://developers.google.com/drive/v2/reference/
     * https://developers.google.com/drive/v3/web/search-parameters
     * @param $file
     * @return \expectedClass|\Google_Http_Request
     */
    protected function remove_duplicated($file) {
        $response = $this->service->files->listFiles([
            'q' => "'$this->folder_id' in parents and name contains '$file' and trashed=false",
        ]);
        foreach ($response->getFiles() as $found) {
            return $this->service->files->delete($found->id);
        }
    }

    /**
     * upload files to GDrive.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function upload_files() {
        Storage::cloud()->put("", "");
        $adapter    = new GoogleDriveAdapter($this->service, Cache::get('folder_id'));
        $filesystem = new Filesystem($adapter);
        // here we are uploading files from local storage
        // we first get all the files
        $files = Storage::files();
        // loop over the found files
        foreach ($files as $file) {
            // remove file from google drive in case we have something under the same name
            // comment out if its okay to have files under the same name
            $this->remove_duplicated($file);
            // read the file content
            $read = Storage::get($file);
            // save to google drive
            $filesystem->write($file, $read);
            // remove the local file
            Storage::delete($file);
        }
    }

    /**
     * @param $filename . Full path to file on local disk
     * @param $drive_name . The name the file should bear when saved on GDrive
     * @param string $mime_type . Mime type of the file
     * @param $folder_id
     * @return mixed
     */
    public function upload_file($filename, $drive_name, $mime_type="", $folder_id) {
        $folderId = $folder_id;
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $drive_name,
            'parents' => array($folderId)
        ));
        $content = file_get_contents($filename);
        $file = $this->service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => ($mime_type != "") ? $mime_type : $fileMetadata->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id'));
        // printf("File ID: %s\n", $file->id);

        return $file->id;
    }


    /**
     ** get the total file count inside a specific folder
     *
     * @param null $folder_id
     * @return int
     */
    public function files_count($folder_id = null) {
        $id = $this->folder_id ?: $folder_id;
        $response = $this->service->files->listFiles([
            'q' => "'$id' in parents and trashed=false",
        ]);
        return count($response->getFiles());
    }
}