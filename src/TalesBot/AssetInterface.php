<?php

declare(strict_types=1);

namespace TalesBot;

/**
 * The base interface all assets must implement.
 */
interface AssetInterface
{
    /**
     * Return information about the asset.
     *
     * - name: The name of the asset.
     * - description: The description of the asset.
     *
     * @return array{
     *   name: string,
     *   description: string,
     * }
     */
    public function getInfo(TalesBot $talesBot): array;
}
