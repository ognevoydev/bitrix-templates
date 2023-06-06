<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<h1 class="bc_title">
    <?= GetMessage("BC_TITLE") ?>
</h1>
<? if (!empty($arResult)) { ?>
    <table class="bc_table">
        <tr>
            <th><?= GetMessage("BC_ITEM_ID") ?></th>
            <th><?= GetMessage("BC_ITEM_NAME") ?></th>
            <th><?= GetMessage("BC_ITEM_ACTIVE_FROM") ?></th>
        </tr>
        <? foreach ($arResult['ITEMS'] as $arItem) { ?>
            <tr>
                <td><?= $arItem["ID"] ?></td>
                <td><?= $arItem["NAME"] ?></td>
                <td><?= $arItem["ACTIVE_FROM"] ?></td>
            </tr>
        <? } ?>
    </table>
<? } ?>
