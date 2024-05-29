$(document).ready(function () {
    $(".nav-link.active .sub-menu").slideDown();
    // $("p").slideUp();

    $("#sidebar-menu .arrow").click(function () {
        $(this).parents("li").children(".sub-menu").slideToggle();
        $(this).toggleClass("fa-angle-right fa-angle-down");
    });

    $("input[name='checkall']").click(function () {
        var checked = $(this).is(":checked");
        $(".table-checkall tbody tr td input:checkbox.can_check").prop(
            "checked",
            checked
        );
    });
    $(".delete_one_link").click(function (e) {
        if (!confirm("Bạn có chắc chắn muốn xóa nội dung này?")) {
            e.preventDefault();
        }
    });
    $("#btn_submit_action").click(function (e) {
        var actionSelect = $("#action_select").val();
        if (actionSelect == "delete") {
            if (!confirm("Bạn có chắc chắn muốn xóa các nội dung này?")) {
                e.preventDefault();
            }
        }
        if (actionSelect == "force_delete") {
            if (
                !confirm(
                    "Bạn có chắc chắn muốn xóa vĩnh viễn các nội dung này?"
                )
            ) {
                e.preventDefault();
            }
        }
        if (actionSelect == "restore") {
            if (!confirm("Bạn có chắc chắn muốn khôi phục các nội dung này?")) {
                e.preventDefault();
            }
        }
    });
    $("a.disabled_link").click(function (e) {
        e.preventDefault();
        $(this).off("click");
    });

    //upload ảnh lên server
    $("#imageInput").change(function () {
        var file = $(this)[0].files[0];

        // Validate file type
        var allowedExtensions = /(\.png|\.jpeg|\.jpg)$/i;
        if (!allowedExtensions.exec(file.name)) {
            $("#image_error").html(
                "<small class='text-danger'>File tải lên không hợp lệ.</small>"
            );
            return false;
        }
        var formData = new FormData();
        formData.append("image", file);
        $.ajax({
            url: "/admin/image/upload",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $("#image_url").val("/" + response[0]);
                $("#imagePreview img").attr("src", "/" + response[0]);
                $("#image_id").val(response[1]);
                $("#image_error").html("");
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            },
        });
    });
});
