<table>
    <thead>
        <tr>
            <th>
                한국어시험번호
            </th>
            <th>
                시행계획일련번호
            </th>
            <th>
                성명
            </th>
            <th>
                임시응시번호
            </th>
            <th>
                시행회차
            </th>
        </tr>
    </thead>

    <tbody>
        <?foreach ($rows as $row):?>
        <tr>
            <td>
                <?=$row['KLNG_EXAM_AYEX_NO'];?>
            </td>
            <td>
                <?=$row['OPRN_PLAN_SEQ'];?>
            </td>
            <td>
                <?=$row['PNM'];?>
            </td>
            <td>
                <?=$row['TMPR_AYEX_NO'];?>
            </td>
            <td>
                <?=$row['OPRN_TME'];?>
            </td>
        </tr>
        <?endforeach;?>
    </tbody>
</table>

<?while($pagination->hasNextPage()):?>
    <a href="./jobfile?page=<?=$pagination->getCurrentPage()?>"><?=$pagination->getCurrentPage()?></a>
<?endwhile;?>
<a href="./jobfile?page=<?=$pagination->getLastPage()?>"><?=$pagination->getLastPage()?></a>