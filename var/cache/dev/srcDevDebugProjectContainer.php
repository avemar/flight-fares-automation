<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerSva3f81\srcDevDebugProjectContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerSva3f81/srcDevDebugProjectContainer.php') {
    touch(__DIR__.'/ContainerSva3f81.legacy');

    return;
}

if (!\class_exists(srcDevDebugProjectContainer::class, false)) {
    \class_alias(\ContainerSva3f81\srcDevDebugProjectContainer::class, srcDevDebugProjectContainer::class, false);
}

return new \ContainerSva3f81\srcDevDebugProjectContainer(array(
    'container.build_hash' => 'Sva3f81',
    'container.build_id' => 'fb084ba0',
    'container.build_time' => 1515451197,
));
