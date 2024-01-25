
<form method="GET" onsubmit="return onSubmit()">
    <table id="search">
        <tr>
            <td>
                검색
            </td>
            <td>
                <div>
                    <label for="company_name">
                        <select>
                            <option>업체명</option>
                        </select>
                    </label>
                    <input type="text" id="company_name" name="company_name" value="<?=$company_name?>"/>
                </div>
                
                <div>
                    <label for="business_code">
                        <select>
                            <option>사업자등록번호</option>
                        </select>
                    </label>
                    <input type="text" id="business_code" name="business_code" value="<?=$business_code?>"/>
                </div>
            </td>
        </tr>
    </table>

    <input type="submit" value="검색"></input>
</form>

<?if (!empty($companies) && count($companies) > 0):?>
<table id="result">
    <tbody>
        <?foreach ($companies as $company):?>
            <tr>
                <td>
                    기업명
                </td>
                <td>
                    <?=$company['company_name']?>
                </td>
            </tr>
            <tr>
                <td>
                    주소
                </td>
                <td>
                    <?=$company['address']?>
                </td>
            </tr>
            <tr>
                <td>
                    범위
                </td>
                <td>
                    <?=$company['range']?>
                </td>
            </tr>
            <tr>
                <td>
                    인증기관
                </td>
                <td>
                    <?=$company['authenticate_company']?>
                </td>
            </tr>
            <tr>
                <td>
                    시스템타입
                </td>
                <td>
                    <?=$company['iso_type']?>
                </td>
            </tr>
            <tr>
                <td>
                    인증번호
                </td>
                <td>
                    <?=$company['authenticate_type']?>
                </td>
            </tr>
            <tr>
                <td>
                    인증현황
                </td>
                <td>
                    <?=$company['authenticate_status']?>
                </td>
            </tr>
        <?endforeach;?>
    </tbody>
</table>
<?endif;?>

<style>
input[type=submit] {
    width: 100%;
    background-color: #0082ff;
    color: #fff;
    padding: 9px 7px;
    border-radius: 5px;
    border: unset;
    margin-top: 30px;
    margin-bottom: 20px;
}
#search {
    width: 100%;
    border: 1px solid #b3b3b3;
    border-top: 3px solid #b3b3b3;
}
#search > tbody > tr > td {
    padding: 5px 7px;
}
#search > tbody > tr > td:nth-child(1) {
    background-color: #eee;
    width: 150px;
}
#result {
    display: block;
    border: 1px solid #b3b3b3;
}
#result > tbody {
    width: 100%;
    display: block;
}
#result > tbody > tr:not(:last-child) {
    border-bottom: 1px solid #b3b3b3;
}
#result > tbody > tr {
    width: 100%;
    display: flex;
}
#result > tbody > tr > td {
    padding: 5px 7px;
}
#result > tbody > tr > td:nth-child(1) {
    width: 100px;
    border-right: 1px solid #b3b3b3;
}
#result > tbody > tr > td:nth-child(2) {
    flex: 1;
}
</style>