<?php declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\ClassLike\RemoveAnnotationRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\FunctionLike\MixedTypeRector;
use Rector\Php80\Rector\FunctionLike\UnionTypesRector;
use Rector\Php81\Rector\FunctionLike\IntersectionTypesRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    // register a single rule
    $rectorConfig->rules([
        InlineConstructorDefaultToPropertyRector::class,
        AddParamTypeSplFixedArrayRector::class,
    ]);

    $rectorConfig->skip([
        RemoveAnnotationRector::class,
        RemoveUselessParamTagRector::class,
        IntersectionTypesRector::class,
        MixedTypeRector::class,
        UnionTypesRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        AddSeeTestAnnotationRector::class,
        RenameClassRector::class,
    ]);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        SymfonySetList::SYMFONY_62,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_CODE_QUALITY,
    ]);
};
