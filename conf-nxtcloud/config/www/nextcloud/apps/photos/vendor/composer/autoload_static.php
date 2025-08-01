<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitPhotos
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'OCA\\Photos\\' => 11,
        ),
        'H' => 
        array (
            'Hexogen\\KDTree\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'OCA\\Photos\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
        'Hexogen\\KDTree\\' => 
        array (
            0 => __DIR__ . '/..' . '/hexogen/kdtree/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Hexogen\\KDTree\\Exception\\ValidationException' => __DIR__ . '/..' . '/hexogen/kdtree/src/Exception/ValidationException.php',
        'Hexogen\\KDTree\\FSKDTree' => __DIR__ . '/..' . '/hexogen/kdtree/src/FSKDTree.php',
        'Hexogen\\KDTree\\FSNode' => __DIR__ . '/..' . '/hexogen/kdtree/src/FSNode.php',
        'Hexogen\\KDTree\\FSTreePersister' => __DIR__ . '/..' . '/hexogen/kdtree/src/FSTreePersister.php',
        'Hexogen\\KDTree\\Interfaces\\ItemFactoryInterface' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/ItemFactoryInterface.php',
        'Hexogen\\KDTree\\Interfaces\\ItemInterface' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/ItemInterface.php',
        'Hexogen\\KDTree\\Interfaces\\ItemListInterface' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/ItemListInterface.php',
        'Hexogen\\KDTree\\Interfaces\\KDTreeInterface' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/KDTreeInterface.php',
        'Hexogen\\KDTree\\Interfaces\\NodeInterface' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/NodeInterface.php',
        'Hexogen\\KDTree\\Interfaces\\PointInterface' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/PointInterface.php',
        'Hexogen\\KDTree\\Interfaces\\SearchAbstract' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/SearchAbstract.php',
        'Hexogen\\KDTree\\Interfaces\\TreePersisterInterface' => __DIR__ . '/..' . '/hexogen/kdtree/src/Interfaces/TreePersisterInterface.php',
        'Hexogen\\KDTree\\Item' => __DIR__ . '/..' . '/hexogen/kdtree/src/Item.php',
        'Hexogen\\KDTree\\ItemFactory' => __DIR__ . '/..' . '/hexogen/kdtree/src/ItemFactory.php',
        'Hexogen\\KDTree\\ItemList' => __DIR__ . '/..' . '/hexogen/kdtree/src/ItemList.php',
        'Hexogen\\KDTree\\KDTree' => __DIR__ . '/..' . '/hexogen/kdtree/src/KDTree.php',
        'Hexogen\\KDTree\\NearestSearch' => __DIR__ . '/..' . '/hexogen/kdtree/src/NearestSearch.php',
        'Hexogen\\KDTree\\Node' => __DIR__ . '/..' . '/hexogen/kdtree/src/Node.php',
        'Hexogen\\KDTree\\Point' => __DIR__ . '/..' . '/hexogen/kdtree/src/Point.php',
        'OCA\\Photos\\Album\\AlbumFile' => __DIR__ . '/../..' . '/lib/Album/AlbumFile.php',
        'OCA\\Photos\\Album\\AlbumInfo' => __DIR__ . '/../..' . '/lib/Album/AlbumInfo.php',
        'OCA\\Photos\\Album\\AlbumMapper' => __DIR__ . '/../..' . '/lib/Album/AlbumMapper.php',
        'OCA\\Photos\\Album\\AlbumWithFiles' => __DIR__ . '/../..' . '/lib/Album/AlbumWithFiles.php',
        'OCA\\Photos\\AppInfo\\Application' => __DIR__ . '/../..' . '/lib/AppInfo/Application.php',
        'OCA\\Photos\\Command\\AlbumAddCommand' => __DIR__ . '/../..' . '/lib/Command/AlbumAddCommand.php',
        'OCA\\Photos\\Command\\AlbumCreateCommand' => __DIR__ . '/../..' . '/lib/Command/AlbumCreateCommand.php',
        'OCA\\Photos\\Command\\UpdateReverseGeocodingFilesCommand' => __DIR__ . '/../..' . '/lib/Command/UpdateReverseGeocodingFilesCommand.php',
        'OCA\\Photos\\Controller\\AlbumsController' => __DIR__ . '/../..' . '/lib/Controller/AlbumsController.php',
        'OCA\\Photos\\Controller\\ApiController' => __DIR__ . '/../..' . '/lib/Controller/ApiController.php',
        'OCA\\Photos\\Controller\\PageController' => __DIR__ . '/../..' . '/lib/Controller/PageController.php',
        'OCA\\Photos\\Controller\\PreviewController' => __DIR__ . '/../..' . '/lib/Controller/PreviewController.php',
        'OCA\\Photos\\Controller\\PublicAlbumController' => __DIR__ . '/../..' . '/lib/Controller/PublicAlbumController.php',
        'OCA\\Photos\\Controller\\PublicPreviewController' => __DIR__ . '/../..' . '/lib/Controller/PublicPreviewController.php',
        'OCA\\Photos\\DB\\PhotosFile' => __DIR__ . '/../..' . '/lib/DB/PhotosFile.php',
        'OCA\\Photos\\DB\\Place\\PlaceFile' => __DIR__ . '/../..' . '/lib/DB/Place/PlaceFile.php',
        'OCA\\Photos\\DB\\Place\\PlaceInfo' => __DIR__ . '/../..' . '/lib/DB/Place/PlaceInfo.php',
        'OCA\\Photos\\DB\\Place\\PlaceMapper' => __DIR__ . '/../..' . '/lib/DB/Place/PlaceMapper.php',
        'OCA\\Photos\\Dashboard\\OnThisDay' => __DIR__ . '/../..' . '/lib/Dashboard/OnThisDay.php',
        'OCA\\Photos\\Exception\\AlreadyInAlbumException' => __DIR__ . '/../..' . '/lib/Exception/AlreadyInAlbumException.php',
        'OCA\\Photos\\Jobs\\AutomaticPlaceMapperJob' => __DIR__ . '/../..' . '/lib/Jobs/AutomaticPlaceMapperJob.php',
        'OCA\\Photos\\Listener\\AlbumsManagementEventListener' => __DIR__ . '/../..' . '/lib/Listener/AlbumsManagementEventListener.php',
        'OCA\\Photos\\Listener\\CSPListener' => __DIR__ . '/../..' . '/lib/Listener/CSPListener.php',
        'OCA\\Photos\\Listener\\ExifMetadataProvider' => __DIR__ . '/../..' . '/lib/Listener/ExifMetadataProvider.php',
        'OCA\\Photos\\Listener\\LoadSidebarScripts' => __DIR__ . '/../..' . '/lib/Listener/LoadSidebarScripts.php',
        'OCA\\Photos\\Listener\\OriginalDateTimeMetadataProvider' => __DIR__ . '/../..' . '/lib/Listener/OriginalDateTimeMetadataProvider.php',
        'OCA\\Photos\\Listener\\PlaceMetadataProvider' => __DIR__ . '/../..' . '/lib/Listener/PlaceMetadataProvider.php',
        'OCA\\Photos\\Listener\\SabrePluginAuthInitListener' => __DIR__ . '/../..' . '/lib/Listener/SabrePluginAuthInitListener.php',
        'OCA\\Photos\\Listener\\SizeMetadataProvider' => __DIR__ . '/../..' . '/lib/Listener/SizeMetadataProvider.php',
        'OCA\\Photos\\Listener\\TagListener' => __DIR__ . '/../..' . '/lib/Listener/TagListener.php',
        'OCA\\Photos\\Migration\\Version20000Date20220727125801' => __DIR__ . '/../..' . '/lib/Migration/Version20000Date20220727125801.php',
        'OCA\\Photos\\Migration\\Version20001Date20220830131446' => __DIR__ . '/../..' . '/lib/Migration/Version20001Date20220830131446.php',
        'OCA\\Photos\\Migration\\Version20003Date20221102170153' => __DIR__ . '/../..' . '/lib/Migration/Version20003Date20221102170153.php',
        'OCA\\Photos\\Migration\\Version20003Date20221103094628' => __DIR__ . '/../..' . '/lib/Migration/Version20003Date20221103094628.php',
        'OCA\\Photos\\Migration\\Version30000Date20240417075405' => __DIR__ . '/../..' . '/lib/Migration/Version30000Date20240417075405.php',
        'OCA\\Photos\\Migration\\Version40000Date20250624085327' => __DIR__ . '/../..' . '/lib/Migration/Version40000Date20250624085327.php',
        'OCA\\Photos\\RepairStep\\InitMetadata' => __DIR__ . '/../..' . '/lib/RepairStep/InitMetadata.php',
        'OCA\\Photos\\Sabre\\Album\\AlbumPhoto' => __DIR__ . '/../..' . '/lib/Sabre/Album/AlbumPhoto.php',
        'OCA\\Photos\\Sabre\\Album\\AlbumRoot' => __DIR__ . '/../..' . '/lib/Sabre/Album/AlbumRoot.php',
        'OCA\\Photos\\Sabre\\Album\\AlbumsHome' => __DIR__ . '/../..' . '/lib/Sabre/Album/AlbumsHome.php',
        'OCA\\Photos\\Sabre\\Album\\PublicAlbumPhoto' => __DIR__ . '/../..' . '/lib/Sabre/Album/PublicAlbumPhoto.php',
        'OCA\\Photos\\Sabre\\Album\\PublicAlbumRoot' => __DIR__ . '/../..' . '/lib/Sabre/Album/PublicAlbumRoot.php',
        'OCA\\Photos\\Sabre\\Album\\SharedAlbumRoot' => __DIR__ . '/../..' . '/lib/Sabre/Album/SharedAlbumRoot.php',
        'OCA\\Photos\\Sabre\\Album\\SharedAlbumsHome' => __DIR__ . '/../..' . '/lib/Sabre/Album/SharedAlbumsHome.php',
        'OCA\\Photos\\Sabre\\CollectionPhoto' => __DIR__ . '/../..' . '/lib/Sabre/CollectionPhoto.php',
        'OCA\\Photos\\Sabre\\PhotosHome' => __DIR__ . '/../..' . '/lib/Sabre/PhotosHome.php',
        'OCA\\Photos\\Sabre\\Place\\PlacePhoto' => __DIR__ . '/../..' . '/lib/Sabre/Place/PlacePhoto.php',
        'OCA\\Photos\\Sabre\\Place\\PlaceRoot' => __DIR__ . '/../..' . '/lib/Sabre/Place/PlaceRoot.php',
        'OCA\\Photos\\Sabre\\Place\\PlacesHome' => __DIR__ . '/../..' . '/lib/Sabre/Place/PlacesHome.php',
        'OCA\\Photos\\Sabre\\PropFindPlugin' => __DIR__ . '/../..' . '/lib/Sabre/PropFindPlugin.php',
        'OCA\\Photos\\Sabre\\PublicAlbumAuthBackend' => __DIR__ . '/../..' . '/lib/Sabre/PublicAlbumAuthBackend.php',
        'OCA\\Photos\\Sabre\\PublicRootCollection' => __DIR__ . '/../..' . '/lib/Sabre/PublicRootCollection.php',
        'OCA\\Photos\\Sabre\\RootCollection' => __DIR__ . '/../..' . '/lib/Sabre/RootCollection.php',
        'OCA\\Photos\\Service\\MediaPlaceManager' => __DIR__ . '/../..' . '/lib/Service/MediaPlaceManager.php',
        'OCA\\Photos\\Service\\ReverseGeoCoderService' => __DIR__ . '/../..' . '/lib/Service/ReverseGeoCoderService.php',
        'OCA\\Photos\\Service\\UserConfigService' => __DIR__ . '/../..' . '/lib/Service/UserConfigService.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitPhotos::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitPhotos::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitPhotos::$classMap;

        }, null, ClassLoader::class);
    }
}
