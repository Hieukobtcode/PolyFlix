$(document).ready(function () {
    const selectAddSeat = $("#select-addSeat");
    const btnAddSeat = $("#btn-addSeat");
    const addHang = $("#add-hang-ghe");
    const addCot = $("#add-cot-ghe");
    const soHangGheTheoLoai = $("#so_luong_hang_ghe_theo_loai");
    const chonHangGhe = $("#chon-hang-ghe");
    const cotGhe = $("#chon-cot-ghe");
    const checkbox = $('input[type="checkbox"]');
    const soLuongCotGhe = $("#so-luong-cot-ghe");
    const rightOrLeft = $("#right-or-left");
    const soGheKhiThemHang = $("#so-ghe-khi-them-hang");
    const btnThemHang = $("#btn-them-hang");
    const btnThemCotGhe = $("#btn-them-ghe");
    selectAddSeat.change(function () {
        let valSelect = $(this).val();
        if (valSelect == 1) {
            addHang.show();
            addCot.hide();
            cotGhe.hide();
            soLuongCotGhe.hide();
            rightOrLeft.hide();
        } else if (valSelect == 2) {
            addCot.show();
            addHang.hide();
            soHangGheTheoLoai.hide();
            chonHangGhe.hide();
            cotGhe.show();

            addHang.val("");
            soHangGheTheoLoai.val("");
            chonHangGhe.val("");
            addCot.val("");
            checkbox.prop("checked", false);
            soLuongCotGhe.val("");
            rightOrLeft.val("");
            soHangGheTheoLoai.val("");
        } else {
            soHangGheTheoLoai.hide();
            chonHangGhe.hide();
            addCot.hide();
            addHang.hide();
            cotGhe.hide();
            soLuongCotGhe.hide();
            rightOrLeft.hide();

            addHang.val("");
            soHangGheTheoLoai.val("");
            chonHangGhe.val("");
            addCot.val("");
            checkbox.prop("checked", false);
            soLuongCotGhe.val("");
            rightOrLeft.val("");
            soHangGheTheoLoai.val("");
        }
    });

    btnAddSeat.click(function () {
        let currentHtml = btnAddSeat.html();

        if (currentHtml.includes("fa-trash")) {
            selectAddSeat.hide();
            addHang.hide();
            addCot.hide();
            soHangGheTheoLoai.hide();
            btnAddSeat.html('<i class="fa-solid fa-gear fa-spin-pulse"></i>');
            btnAddSeat.css("background-color", "");
            chonHangGhe.hide();
            cotGhe.hide();
            soGheKhiThemHang.hide();
            rightOrLeft.hide();
            soLuongCotGhe.hide();
            selectAddSeat.val("");
            addHang.val("");
            soHangGheTheoLoai.val("");
            chonHangGhe.val("");
            addCot.val("");
            checkbox.prop("checked", false);
            soLuongCotGhe.val("");
            rightOrLeft.val("");
            soHangGheTheoLoai.val("");
            soGheKhiThemHang.val("");
        } else {
            selectAddSeat.show();
            btnAddSeat.html('<i class="fa-solid fa-trash fa-shake"></i>');
            btnAddSeat.css("background-color", "#e36060");
        }
    });

    addHang.change(function () {
        if (addHang.val() == 0) {
            soHangGheTheoLoai.hide();
        } else {
            soHangGheTheoLoai.show();
            soHangGheTheoLoai.data("loai-ghe-id", addHang.val());
        }
    });
    soHangGheTheoLoai.on("input", function () {
        if (soHangGheTheoLoai.val() > 0) {
            soGheKhiThemHang.show();
        } else {
            soGheKhiThemHang.hide();
        }
    });

    checkbox.change(function () {
        if ($(".form-check-input:checked").length > 0) {
            soLuongCotGhe.show();
        } else {
            soLuongCotGhe.hide();
        }
    });

    soLuongCotGhe.on("input", function () {
        if (soLuongCotGhe.val() > 0) {
            rightOrLeft.show();
        } else {
            rightOrLeft.hide();
        }
    });

    chonHangGhe.change(function () {
        if (chonHangGhe.val() == 1) {
        } else if (chonHangGhe.val() == 2) {
        } else {
            chonHangGhe.val("");
        }
    });

    soGheKhiThemHang.on("input", function () {
        if ($(this).val() > 0) {
            btnThemHang.show();
        } else {
            btnThemHang.hide();
        }
    });

    btnThemHang.on("click", function () {
        $(this).hide();

        const loaiChon = parseInt(addHang.val());
        const tenLoaiGhe =
            window.tenLoaiGheMap?.[String(loaiChon)] || "Không rõ";
        const soHangThem = parseInt(soHangGheTheoLoai.val());
        if (soHangThem === 0) return;

        const danhSach = layDanhSachHang();

        const indexChen = danhSach.findIndex((h) => h.loai === loaiChon);
        const viTriChen = indexChen >= 0 ? indexChen : danhSach.length;

        // Dịch các hàng bên dưới xuống
        for (let i = danhSach.length - 1; i >= viTriChen; i--) {
            const hangCu = danhSach[i].hang;
            const hangMoi = String.fromCharCode(
                hangCu.charCodeAt(0) + soHangThem
            );

            $(`.seat-wrapper[data-row='${hangCu}']`).each(function () {
                const col = $(this).data("col");
                const maGheMoi = hangMoi + col;

                $(this).attr("data-row", hangMoi);
                $(this).attr("data-seat", maGheMoi);
                $(this).find(".seat-code").text(maGheMoi);
            });
        }

        const soCot = parseInt(soGheKhiThemHang.val());
        let baseCharCode = danhSach[viTriChen - 1]
            ? danhSach[viTriChen - 1].hang.charCodeAt(0)
            : 64;

        let allHangHTML = "";
        for (let i = 0; i < soHangThem; i++) {
            const hangMoi = String.fromCharCode(baseCharCode + i + 1);
            let hangHTML = `<div class="seat-row" data-row="${hangMoi}" style="display: flex; align-items: center;">`;

            for (let j = 1; j <= soCot; j++) {
                const maGhe = hangMoi + j;
                hangHTML += `
                <div style="background-color: #ccc;"
                    data-loai="${loaiChon}" data-id=""
                    class=" {{ $isDouble }} seat-wrapper {{ $oneSeat['trang_thai'] === 'bao_tri' ? 'selected' : '' }}"
                    data-seat="${maGhe}" data-row="${hangMoi}"
                    data-col="${j}">
                    <i class="fa-solid fa-couch"></i>
                    <span class="seat-code">${maGhe}</span>
                </div>`;
            }

            hangHTML += `</div>`;

            allHangHTML += hangHTML;
        }

        const $refRow = $(`.seat-row`)
            .filter(function () {
                return (
                    $(this).find(`.seat-wrapper[data-loai='${loaiChon}']`)
                        .length > 0
                );
            })
            .first();

        if ($refRow.length > 0) {
            $refRow.before(allHangHTML);
        } else {
            $(".seat-map").append(allHangHTML);
        }

        Swal.fire({
            icon: "success",
            title: "Thêm hàng ghế thành công!",
            html: `Đã thêm <strong>${soHangThem}</strong> hàng ghế<br> 
            Hãy nhấn <strong>Cập nhật</strong> để lưu thay đổi.`,
            confirmButtonText: "Đã hiểu",
        });
    });

    rightOrLeft.change(function () {
        if ($(this).val() === "") {
            btnThemCotGhe.hide();
        } else {
            btnThemCotGhe.show();
        }
    });

    $(".seat-wrapper:not(.empty)").on("click", function () {
        const $seat = $(this);
        const isDouble = $seat.hasClass("doi");
        const row = $seat.data("row");
        const col = parseInt($seat.data("col"));

        if (isDouble) {
            const partnerCol = col % 2 === 0 ? col - 1 : col + 1;
            const $partnerSeat = $(
                `.seat-wrapper.doi[data-row="${row}"][data-col="${partnerCol}"]`
            );
            $seat.toggleClass("selected");
            if ($partnerSeat.length) {
                $partnerSeat.toggleClass("selected");
            }
        } else {
            $seat.toggleClass("selected");
        }
    });

    $("#updateSeatForm").on("submit", function (e) {
        const seats = $(".seat-wrapper.selected")
            .map(function () {
                return $(this).data("id");
            })
            .get();
        $("#hiddenSeatsJson").val(JSON.stringify(seats));

        const allSeat = [];
        let hasNewSeat = false;

        $(".seat-wrapper").each(function () {
            const sofa = $(this);
            const id = sofa.data("id") || null;

            if (!id) {
                hasNewSeat = true; 
            }

            allSeat.push({
                id: id,
                row: sofa.data("row"),
                col: sofa.data("col"),
                seat_code: sofa.data("seat"),
                loai: sofa.data("loai") || null,
            });
            
        });
        alert(JSON.stringify(allSeat));


        if (hasNewSeat) {
            $("#allSeat").val(JSON.stringify(allSeat));
        }
    });

    function layDanhSachHang() {
        const danhSach = [];
        $(".seat-wrapper").each(function () {
            const hang = $(this).data("row");
            const loai = $(this).data("loai");

            if (!danhSach.find((h) => h.hang === hang)) {
                danhSach.push({
                    hang,
                    loai,
                });
            }
        });
        danhSach.sort((a, b) => a.hang.charCodeAt(0) - b.hang.charCodeAt(0));

        return danhSach;
    }
    btnThemCotGhe.on("click", function () {
        $(this).hide();

        const selectedCheckbox = $("input[name='checkbox[]']:checked")
            .map(function () {
                return this.value;
            })
            .get();

        const soCotThem = parseInt(soLuongCotGhe.val());
        const hangThem = parseInt(rightOrLeft.val()); 

        const hangGhe = $(".seat-row").filter(function () {
            const row = $(this).data("row");
            return selectedCheckbox.includes(row);
        });

        hangGhe.each(function () {
            const rowDiv = $(this);
            const rowName = rowDiv.data("row");
            const lastSeat = rowDiv.children(".seat-wrapper").last();
            const lastCol = parseInt(lastSeat.data("col")) || 0;
            const loai = rowDiv.children(".seat-wrapper").first().data("loai");
            if (hangThem === 1) {
                for (let i = 1; i <= soCotThem; i++) {
                    const col = lastCol + i;
                    const seatCode = rowName + col;

                    const gheHTML = `
                    <div style="background-color: #ccc"
                        data-loai="${loai}"  data-id=""
                        class="{{ $isDouble }}  seat-wrapper {{ $oneSeat['trang_thai'] === 'bao_tri' ? 'selected' : ''" 
                        data-seat="${seatCode}" 
                        data-row="${rowName}" 
                        data-col="${col}">
                        <i class="fa-solid fa-couch"></i>
                        <span class="seat-code">${seatCode}</span>
                    </div>
                `;
                    rowDiv.append(gheHTML);
                }
            } else {
                for (let i = 1; i <= soCotThem; i++) {
                    const tempSeat = `
                    <div style="background-color: #ccc"
                        data-loai="${loai}" data-id=""
                        class="{{ $isDouble }} seat-wrapper {{ $oneSeat['trang_thai'] === 'bao_tri' ? 'selected' : ''" 
                        data-seat="${seatCode}" 
                        data-row="${rowName}" 
                        data-col="${col}">
                        <i class="fa-solid fa-couch"></i>
                        <span class="seat-code"></span>
                    </div>
                `;
                    rowDiv.prepend(tempSeat);
                }

                rowDiv.children(".seat-wrapper").each(function (index) {
                    const col = index + 1;
                    const seatCode = rowName + col;
                    $(this).attr("data-row",rowName);
                    $(this).attr("data-col", col);
                    $(this).attr("data-seat", seatCode);
                    $(this).find(".seat-code").text(seatCode);
                });
            }
        });
        const hangNames = hangGhe
            .map(function () {
                return $(this).data("row");
            })
            .get()
            .join(", ");

        Swal.fire({
            icon: "success",
            title: "Thêm ghế thành công!",
            html: `Đã thêm <strong>${soCotThem}</strong> ghế vào hàng: <strong>${hangNames}</strong>.<br> 
            Hãy nhấn <strong>Cập nhật</strong> để lưu thay đổi.`,
            confirmButtonText: "Đã hiểu",
        });
    });
});
