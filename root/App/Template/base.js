function onSubmit() {
    if (!document.getElementById("company_name").value) {
        alert("업체명을 입력해주세요");
        return false;
    }

    if (!document.getElementById("business_code").value) {
        alert("사업자등록번호를 입력해주세요");
        return false;
    }

    location.href = "/" + document.getElementById("company_name").value + "/" + document.getElementById("business_code").value;
    return false;
}