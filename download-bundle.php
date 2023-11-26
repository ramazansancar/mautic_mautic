<?php

$realPath = realpath(__DIR__.'/..');

$key = $_GET['key'];

$owner = $_GET['owner'];
$repo = $_GET['repo'];
$bundleName = $_GET['bundle'];


$hasher = new hasher($owner, $repo, $bundleName);
//die(print_r($hasher->getKey()));

//https://madesimple.madesimple.cloud/download-bundle.php?owner=kuzmany&repo=form-designer-bundle&bundle=FormDesignerBundle&key=b755742de7422d3a2fddb890400dd75db957cf0a

// https://madesimple.madesimple.cloud/download-bundle.php?owner=kuzmany&repo=better-bundle&bundle=BetterBundle&key=9788130ac8096ac6d6b72b49b40cfd440c08d07b

//http://madesimple.madesimple.cloud/download-bundle.php?owner=kuzmany&repo=mautic-twig-templates-bundle&bundle=MauticTwigTemplatesBundle&key=c5729e731856ba51ce94f2ec9929fead93224f19

if (!$hasher->isValid($key)) {
    echo 'Key si not valid. Please contact use' ;
    die();
}

$repoPath = $owner.'/'.$repo;

$tagName = trim(shell_exec('curl -s -u '.$hasher->getAccessKey().':x-oauth-basic https://api.github.com/repos/'.$repoPath.'/releases/latest | grep -oP \'"tag_name": "\K(.*)(?=")\''));

if (isset($_GET['tag'])) {
    if ($_GET['tag'] == 'mautic2') {
        switch ($repo) {
            case 'mautic-custom-unsubscribe-bundle':
            case 'mautic-cron-tester-bundle':
            case 'mautic-form-actions-bundle':
                $tagName = '1.0.0';
                break;
            case 'mautic-limiter-bundle':
                $tagName = '1.1.0';
                break;
            case 'mautic-custom-sms-bundle':
                $tagName = '1.1.1';
                break;
        }
    } else {
        if ($_GET['tag'] == 'mautic3') {
            switch ($repo) {
                case 'mautic-twig-templates-bundle':
                case 'mautic-form-actions-bundle':
                    $tagName = '1.2.0';
                    break;
            }
        } else {
            $tagName = $_GET['tag'];
        }
    }
}

$downloadLink = 'https://github.com/'.$repoPath.'/archive/'.$tagName.'.zip';
$versionName = $repo.'-'.$tagName;

// clean before downloding
shell_exec('rm -r '.$bundleName);
shell_exec('rm '.$bundleName.'.zip');
shell_exec(
    'curl -O -J -L -u '.$hasher->getAccessKey().':x-oauth-basic '.$downloadLink
);
if (!file_exists($versionName.'.zip')) {
    echo 'license key is not valid';
    die();
}

shell_exec('unzip '.$versionName.'.zip');
shell_exec('mv '.$versionName.' '.$bundleName);
shell_exec('rm '.$versionName.'.zip');
$zip = $bundleName.'.zip';
shell_exec('zip -r '.$bundleName.' '.$bundleName);

header("Content-type: application/zip");
header("Content-Disposition: attachment; filename=".$zip);
header("Content-length: " . filesize($zip));
header("Pragma: no-cache");
header("Expires: 0");
readfile($zip);
echo 'Download here: <a href="https://madesimple.madesimple.cloud/'.$bundleName.'.zip">'.$bundleName.'.zip</a>';
die();

class hasher
{
    private $owner;

    private $repo;

    private $bundle;

    /**
     * hasher constructor.
     *
     * @param $owner
     * @param $repo
     * @param $bundle
     */
    public function __construct($owner, $repo, $bundle)
    {

        $this->owner = $owner;
        $this->repo = $repo;
        $this->bundle = $bundle;
        $this->privateKey = 'JsVx0JsaJ^SqeK3I';
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        return ($key == $this->getKey());
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return hash('sha1', $this->getSecretKey());
    }

    public function getAccessKey()
    {
        return '052f24929f62da458dda59dec2d6a60d5ef6ef83';
    }

    private function getSecretKey()
    {
        return sprintf("%s%s%s%s", $this->owner, $this->repo, $this->bundle, $this->privateKey);
    }

}



/*
rm mautic-multiple-bundle-master;
cd ..;
php app/console cache:clear --no-warmup;
php app/console mautic:plugins:reload;*/