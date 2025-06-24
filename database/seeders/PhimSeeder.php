<?php

namespace Database\Seeders;

use App\Models\ChiNhanh;
use App\Models\DinhDangPhim;
use App\Models\PhuDePhim;
use App\Models\RapPhim;
use App\Models\TheLoaiPhim;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PhimSeeder extends Seeder
{
    public function run(): void
    {

        $phimData = [
            [
                'ten_phim' => 'Elio Cậu Bé Đến Từ Trái Đất',
                'mo_ta' => 'Elio là một cậu bé đam mê vũ trụ với trí tưởng tượng phong phú. Một hôm, cậu bất ngờ phải tham gia một cuộc phiêu lưu ngoài vũ trụ, nơi cậu phải xây dựng mối quan hệ mới với các dạng sống ngoài hành tinh. Elio phải vượt qua cuộc chiến ở quy mô liên thiên hà và khám phá ra con người thực sự của mình.',
                'dao_dien' => 'Adrian Molina, Madeline Sharafian, Domee Shi',
                'dien_vien' => 'Yonas Kibreab, Zoe Saldana, Brad Garrett',
                'thoi_luong' => 97,
                'ngay_phat_hanh' => '2025-06-21',
                'ngay_ket_thuc' => '2025-07-10',
                'trailer' => 'https://youtu.be/rdzfDoJcrxA',
                'poster' => 'posters/elio.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T13',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => '28 Năm Sau Tận Thế',
                'mo_ta' => 'Cơn ác mộng chưa kết thúc. Virus trở lại, kéo theo bóng tối bao trùm nước Anh. Một hành trình sinh tử: cậu bé tìm kiếm bác sĩ để cứu mẹ mình, băng qua vùng đất chết chóc đầy xác sống tiến hóa và những kẻ nguy hiểm ẩn sau gương mặt tử tế. Liệu niềm hy vọng cuối cùng có đủ để cứu họ khỏi vực thẳm tuyệt vọng?',
                'dao_dien' => 'Adrian Molina, Madeline Sharafian',
                'dien_vien' => 'Aaron Taylor-Johnson,Ralph Fiennes, Alfie Williams',
                'thoi_luong' => 114,
                'ngay_phat_hanh' => '2025-06-23',
                'ngay_ket_thuc' => '2025-07-15',
                'trailer' => 'https://youtu.be/uyKdDzo6rSU',
                'poster' => 'posters/28namsautanthe.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T18',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Bí Kíp Luyện Rồng',
                'mo_ta' => 'Câu chuyện về một chàng trai trẻ với ước mơ trở thành thợ săn rồng, nhưng định mệnh lại đưa đẩy anh đến tình bạn bất ngờ với một chú rồng.',
                'dao_dien' => 'Dean DeBlois',
                'dien_vien' => 'Mason Thames, Nico Parker, Gerard Butler',
                'thoi_luong' => 126,
                'ngay_phat_hanh' => '2025-06-25',
                'ngay_ket_thuc' => '2025-07-12',
                'trailer' => 'https://youtu.be/6lnYqNYj0o8',
                'poster' => 'posters/bikipluyenrong.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Bóng Ma Cõi Mạng',
                'mo_ta' => 'Trong nỗ lực cứu vãn kênh Youtube tâm huyết, Jyujuring quyết định tổ chức livestream ngay tại một ngôi nhà hoang bí ẩn, nơi mà chưa ai từng dám đặt chân đến. Giây phút cánh cửa mở ra cũng là lúc trò "câu view" hóa thành cơn ác mộng tồi tệ nhất cho những kẻ phạm phải điều cấm kỵ.',
                'dao_dien' => 'Vince Kim',
                'dien_vien' => 'Oh Ha-nee; Go I-gyoung; Joseph Kim',
                'thoi_luong' => 91,
                'ngay_phat_hanh' => '2025-06-23',
                'ngay_ket_thuc' => '2025-07-09',
                'trailer' => 'https://youtu.be/ArZfowhLY68',
                'poster' => 'posters/NI966urGg1T1uD9OPAHUXDCg6pVzAG4ie0cOaTGp.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T18',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Út Lan: Oán Linh Giữ Của',
                'mo_ta' => 'Út Lan: Oán Linh Giữ Của diễn ra sau sự ra đi của cha, Lan (Phương Thanh) về một vùng quê và ở đợ cho nhà ông Danh (Mạc Văn Khoa) - một người đàn ông góa vợ, không con cái. Ngay sau khi bước chân vào căn nhà, Lan phải đối mặt với hàng loạt hiện tượng kỳ dị và những cái chết bí ẩn liên tục xảy ra. Cùng với Sơn (Quốc Trường) - một nhà văn chuyên viết truyện kinh dị, Lan bắt đầu lật mở những bí mật kinh hoàng, khám phá lịch sử đen tối của căn nhà.',
                'dao_dien' => 'Trần Trọng Dần',
                'dien_vien' => 'Mạc Văn Khoa, Quốc Trường, Mai Cát Vi',
                'thoi_luong' => 91,
                'ngay_phat_hanh' => '2025-06-27',
                'ngay_ket_thuc' => '2025-07-09',
                'trailer' => 'https://youtu.be/v6QhbIUdMaQ',
                'poster' => 'posters/utlan.jpg',
                'ngon_ngu' => 'Tiếng Việt',
                'quoc_gia' => 'Việt ',
                'do_tuoi' => 'T18',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Phim Điện Ảnh Doraemon: Nobita Và Cuộc Phiêu Lưu Vào Thế Giới Trong Tranh',
                'mo_ta' => 'Cùng nhóm bạn Doraemon trở về châu Âu thế kỉ 13, vào thế giới trong tranh ở phim mới Doraemon The Movie: Nobitas Art World Tales.Chuyến phiêu lưu mạo hiểm vượt thời không tới công quốc Artoria sẽ mang đến bất ngờ gì? ',
                'dao_dien' => 'Yukiyo Teramoto',
                'dien_vien' => 'Mizuta Wasabi, Ogata Megumi, Kakazu Yumi',
                'thoi_luong' => 105,
                'ngay_phat_hanh' => '2025-06-23',
                'ngay_ket_thuc' => '2025-07-09',
                'trailer' => 'https://youtu.be/w6ytXVyupwU',
                'poster' => 'posters/doraemon.jpg',
                'ngon_ngu' => 'Tiếng Nhật',
                'quoc_gia' => 'Nhật Bản',
                'do_tuoi' => 'T13',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Kỳ Án Trên Đồi Tuyết',
                'mo_ta' => 'Cuộc sống của nhà văn Sandra cùng chồng Samuel và cậu con trai mù Daniel ở căn nhà gỗ hẻo lánh trên dãy Alps bất ngờ bị xáo trộn khi Samuel được tìm thấy đã chết một cách bí ẩn trên tuyết, khiến Sandra trở thành nghi phạm chính và mối quan hệ đầy mâu thuẫn giữa cô và chồng dần được phơi bày trước phiên tòa.',
                'dao_dien' => 'Justine Triet',
                'dien_vien' => 'Sandra Hüller, Swann Arlaud, Milo Machado-Graner',
                'thoi_luong' => 152,
                'ngay_phat_hanh' => '2025-06-23',
                'ngay_ket_thuc' => '2025-07-10',
                'trailer' => 'https://youtu.be/EC743LjS76Y',
                'poster' => 'posters/kyantrendoituyet.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T18',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Halabala: Rừng Ma Tế Xác',
                'mo_ta' => 'Thanh tra Dan – kẻ mang biệt danh rùng rợn “Dan Trăm Xác” – là một cảnh sát liều mạng, nổi tiếng với quá khứ đẫm máu và những phi vụ bất chấp luật lệ. Sau một sai lầm kinh hoàng trong lúc thực hiện nhiệm vụ, Dan bị giáng chức và chuyển công tác về vùng hẻo lánh. Với nổ lực để có cơ hội trở lại Bangkok – anh cần bắt được Tup Ta Fai: tên trùm tội phạm loạn trí vừa trốn khỏi ngục, hiện đang ẩn náu trong khu rừng cấm Halabala. Thế nhưng, Halabala không phải một khu rừng bình thường. Nơi đây bị nguyền rủa bởi truyền thuyết quỷ Bataya và Batow – tộc người ăn thịt từng sống trong rừng sâu và đang nuôi dưỡng con quỷ Bataya bằng hận thù và xác người. Trong cuộc truy đuổi đẫm máu giữa rừng thiêng, Dan không chỉ phải đối đầu với Ta Fai, mà còn bị ám ảnh bởi những tiếng gọi ma quái, những ám ảnh dị dạng và nỗi sợ sâu kín nhất của chính anh. Khi vợ anh – Vi – đang mang thai sắp sinh và lạc giữa rừng, Dan buộc phải chọn: công lý… hay sự an toàn của gia đình mình?',
                'dao_dien' => 'Eakasit Thairaat',
                'dien_vien' => 'Chantavit Dhanasevi, Nuttanicha Dungwattanawanich, Anon Saisangchan, Yasaka Chaisorn',
                'thoi_luong' => 90,
                'ngay_phat_hanh' => '2025-06-27',
                'ngay_ket_thuc' => '2025-07-11',
                'trailer' => 'https://youtu.be/IuVL-t9fA9k',
                'poster' => 'posters/hala.jpg',
                'ngon_ngu' => 'Thái lan',
                'quoc_gia' => 'Thái lan',
                'do_tuoi' => 'T18',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'M3GAN 2.0',
                'mo_ta' => 'Nội dung phim M3GAN 2.0 lấy bối cảnh 2 năm sau các sự kiện ở phần 1. Lúc này, Gemma phát hiện công nghệ sản xuất MEGAN đã bị đánh cắp. Kẻ gian đã tạo ra một robot AI khác với chức năng tương tự MEGAN, nhưng được trang bị sức mạnh chiến đấu "khủng" hơn mang tên Amelia. Để "đối đầu" với Amelia, Gemma buộc phải "hồi sinh" và cải tiến MEGAN, hứa hẹn một trận chiến "nảy lửa" trên màn ảnh vào năm 2025.',
                'dao_dien' => 'Gerard Johnstone',
                'dien_vien' => 'Jemaine Clement; Amie Donald; Allison Williams',
                'thoi_luong' => 120,
                'ngay_phat_hanh' => '2025-06-27',
                'ngay_ket_thuc' => '2025-07-08',
                'trailer' => 'https://youtu.be/TIlaeoIHOPo',
                'poster' => 'posters/megan2.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],


            [
                'ten_phim' => 'F1®',
                'mo_ta' => 'F1® kể về Sonny Hayes (Brad Pitt) được mệnh danh là "Huyền thoại chưa từng được gọi tên" là ngôi sao sáng giá nhất của FORMULA 1 trong những năm 1990 cho đến khi một vụ tai nạn trên đường đua suýt nữa đã kết thúc sự nghiệp của anh.. Ba mươi năm sau, Sonny trở thành một tay đua tự do, cho đến khi người đồng đội cũ của anh, Ruben Cervantes (Javier Bardem), chủ sở hữu một đội đua F1 đang trên bờ vực sụp đổ, tìm đến anh. Ruben thuyết phục Sonny quay lại với F1® để có một cơ hội cuối cùng cứu lấy đội và khẳng định mình là tay đua xuất sắc nhất thế giới. Anh sẽ thi đấu cùng Joshua Pearce (Damson Idris), tay đua tân binh đầy tham vọng của đội, người luôn muốn tạo ra tốc độ của riêng mình. Tuy nhiên, khi động cơ gầm rú, quá khứ của Sonny sẽ đuổi theo anh và anh nhận ra rằng trong F1, người đồng đội chính là đối thủ cạnh tranh lớn nhất—và con đường chuộc lại lỗi lầm không phải là điều có thể đi một mình.',
                'dao_dien' => 'Joseph Kosinski',
                'dien_vien' => 'Brad Pitt, Simone Ashley, Javier Bardem,...',
                'thoi_luong' => 156,
                'ngay_phat_hanh' => '2025-06-25',
                'ngay_ket_thuc' => '2025-07-08',
                'trailer' => 'https://youtu.be/llrZvUGzUUk',
                'poster' => 'posters/F1.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Quan Tài Vợ Quỷ',
                'mo_ta' => 'Sau khi Lunthom chết, người chồng và cô tình nhân những tưởng sẽ được hưởng khối gia sản kếch sù. Tuy nhiên người vợ quá cố đã để lại một điều kiện lạnh sống lưng. Đôi tình nhân sẽ chỉ nhận được gia tài khi sống chung 100 ngày với chiếc quan tài kính chứa thi thể Lunthom đặt giữa nhà. Nỗi phẫn uất của người bị phản bội đã biến Lunthom thành quỷ dữ và quay về gieo rắc kinh hoàng.',
                'dao_dien' => 'Vathanyu Ingkawiwat',
                'dien_vien' => 'Woranuch BhiromBhakdi, Arachaporn Pokinpakorn, Thanavate Siriwattanagul',
                'thoi_luong' => 92,
                'ngay_phat_hanh' => '2025-06-29',
                'ngay_ket_thuc' => '2025-07-08',
                'trailer' => 'https://youtu.be/XGQwwngSURQ',
                'poster' => 'posters/quantaivoquy.jpg',
                'ngon_ngu' => 'Thái Lan',
                'quoc_gia' => 'Thái Lan',
                'do_tuoi' => 'T18',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Tổ Đội Gấu Nhí: Du Hí 4 Phương',
                'mo_ta' => 'Vì một sai lầm đáng trách của shipper Cò, gấu Mic Mic tiếp tục phải lên đường tham gia vào một cuộc phiêu lưu bất đắc dĩ và không kém phần ly kỳ để tìm lại bé con của anh. Nhóm bạn lầy lội của Mic-Mic phải lao vào cuộc đua đổi trả em bé qua các châu lục, đối mặt với kangaroo nhảy nhót ở châu Đại Dương, hươu cao cổ kiêu kỳ ở savan châu Phi, dê núi tinh nghịch trên dãy Alps châu Âu, và thậm chí là một chú rồng phun lửa huyền thoại ở Trung Quốc cổ kính! Giữa những màn rượt đuổi nghẹt thở và tiếng cười nghiêng ngả, Mic-Mic phát hiện gấu con thật bị bắt cóc bởi con trăn gian xảo cùng hai kền kền lắm drama, buộc cả đội hợp sức với những người bạn khác để giải cứu. Liệu rằng Mic Mic sẽ bất lực trước hiện thực hay sẽ vùng lên để tìm kiếm hạnh phúc trọn vẹn cho cả gia đình?',
                'dao_dien' => 'Vasiliy Rovenskiy',
                'dien_vien' => 'Woranuch BhiromBhakdi, Arachaporn Pokinpakorn, Thanavate Siriwattanagul',
                'thoi_luong' => 87,
                'ngay_phat_hanh' => '2025-06-29',
                'ngay_ket_thuc' => '2025-07-08',
                'trailer' => 'https://youtu.be/9mH_FlO6oa0',
                'poster' => 'posters/gaunhi.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T13',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Superman',
                'mo_ta' => 'Mùa hè tới đây, Warner Bros. Pictures sẽ mang “Superman” - phim điện ảnh đầu tiên của DC Studios đến các rạp chiếu trên toàn cầu. Với phong cách riêng biệt của mình, James Gunn sẽ khắc họa người hùng huyền thoại trong vũ trụ DC hoàn toàn mới, với sự kết hợp độc đáo của các yếu tố hành động đỉnh cao, hài hước và vô cùng cảm xúc. Một Superman với lòng trắc ẩn và niềm tin vào sự thiện lương của con người sẽ xuất hiện đầy hứa hẹn trên màn ảnh.',
                'dao_dien' => ' James Gunn',
                'dien_vien' => ' David Corenswet, Rachel Brosnahan, Nicholas Hoult,...',
                'thoi_luong' => 99,
                'ngay_phat_hanh' => '2025-06-29',
                'ngay_ket_thuc' => '2025-07-08',
                'trailer' => 'https://youtu.be/whOQyYNdwAs',
                'poster' => 'posters/batman.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Phim Xì Trum',
                'mo_ta' => 'The Smurfs Movie / Phim Xì Trum kể câu chuyện về ngôi làng Xì Trum, nơi mà mỗi ngày đều là lễ hội. Bỗng một ngày, sự yên bình của ngôi làng bị phá vỡ khi Tí Vua bị bắt cóc một cách bí ẩn bởi hai phù thủy độc ác Gà Mên và Cà Mên. Từ đây, Tí Cô Nương phải dẫn dắt các Tí đi vào thế giới thực để giải cứu ông. Với sự giúp đỡ của những người bạn mới, các Tí sẽ bước vào cuộc phiêu lưu khám phá định mệnh của mình để cứu lấy vũ trụ.',
                'dao_dien' => 'Chris Miller',
                'dien_vien' => ' David Corenswet, Rachel Brosnahan, Nicholas Hoult,...',
                'thoi_luong' => 99,
                'ngay_phat_hanh' => '2025-06-29',
                'ngay_ket_thuc' => '2025-07-05',
                'trailer' => 'https://youtu.be/LoB7btq9Bpo',
                'poster' => 'posters/xitrum.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T13',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Thế Giới Khủng Long: Tái Sinh',
                'mo_ta' => 'Phim mới Jurassic World: Rebirth / Thế Giới Khủng Long: Tái Sinh mở ra một chương mới đầy tính hành động, chứng kiến một đội khai thác chạy đua đến nơi nguy hiểm nhất trên Trái Đất. Dàn nhân vật chính là bộ ba Scarlett Johansson, Mahershala Ali và Jonathan Bailey dấn thân vào một nhiệm vụ cực kỳ hiểm nguy, đó chính là cố gắng lấy DNA có thể dẫn đến một bước đột phá y học cho nhân loại. Chìa khóa của nó tình cờ lại là DNA của ba con khủng long khổng lồ nhất trên cạn, biển và không trung trong sinh quyển nhiệt đới. Hành trình này sẽ đưa nhóm nhân vật chính băng rừng, vượt biển, đối mặt với nhiều loài khủng long kỳ lạ, nguy hiểm nhưng cũng đầy lý thú, từ đó hé mở nhiều điều bí ẩn mà tạo hóa đã giấu khỏi con người suốt bấy lâu nay.',
                'dao_dien' => 'Gareth Edwards',
                'dien_vien' => ' David Corenswet, Rachel Brosnahan',
                'thoi_luong' => 120,
                'ngay_phat_hanh' => '2025-06-24',
                'ngay_ket_thuc' => '2025-07-03',
                'trailer' => 'https://youtu.be/LoB7btq9Bpo',
                'poster' => 'posters/khunglong.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Mùa Hè Kinh Hãi',
                'mo_ta' => 'Khi năm người bạn vô tình gây ra một vụ tai nạn xe hơi chết người, họ quyết định che giấu và lập một giao ước giữ bí mật thay vì phải đối mặt với hậu quả. Một năm sau, quá khứ trở lại ám ảnh họ, buộc nhóm bạn phải đối diện với một sự thật khủng khiếp: có ai đó biết những gì họ đã làm vào mùa hè năm ngoái… và quyết tâm trả thù họ. Khi từng người trong nhóm bị kẻ sát nhân truy đuổi, họ phát hiện ra rằng điều này đã xảy ra trước đây. Cả nhóm tìm đến hai người sống sót từ vụ thảm sát huyền thoại ở Southport năm 1997 để cầu cứu.',
                'dao_dien' => 'Gareth Edwards',
                'dien_vien' => ' David Corenswet, Rachel Brosnahan',
                'thoi_luong' => 120,
                'ngay_phat_hanh' => '2025-06-29',
                'ngay_ket_thuc' => '2025-07-10',
                'trailer' => 'https://youtu.be/NQeFJXBt8Ro',
                'poster' => 'posters/muahe.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Bộ Tứ Siêu Đẳng: Bước Đi Đầu Tiên',
                'mo_ta' => 'The Fantastic Four: First Steps / Bộ Tứ Siêu Đẳng: Bước Đi Đầu Tiên kể về một gia đình của Marvel đối mặt với thử thách khó khăn, họ vừa phải cân bằng vai trò là anh hùng với sức mạnh của mối quan hệ gia đình, vừa phải bảo vệ Trái đất khỏi một vị thần không gian hung dữ tên là Galactus và sứ giả của hắn, Silver Surfer.',
                'dao_dien' => 'Matt Shakman',
                'dien_vien' => ' David Corenswet, Rachel Brosnahan',
                'thoi_luong' => 110,
                'ngay_phat_hanh' => '2025-07-03',
                'ngay_ket_thuc' => '2025-07-15',
                'trailer' => 'https://youtu.be/c5JQQzRnVyo',
                'poster' => 'posters/bo4sieudang.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'The Conjuring: Nghi Lễ Cuối Cùng',
                'mo_ta' => 'Cuộc phiêu lưu cuối cùng của nhà Warren.',
                'dao_dien' => 'Michael Chaves',
                'dien_vien' => ' David Corenswet, Rachel Brosnahan',
                'thoi_luong' => 120,
                'ngay_phat_hanh' => '2025-07-05',
                'ngay_ket_thuc' => '2025-07-17',
                'trailer' => 'https://youtu.be/R6zHFk1LlXw',
                'poster' => 'posters/nghilecuoicung.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T18',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Wicked: Phần 2',
                'mo_ta' => 'Wicked: Phần 2 là chương kết xúc động và mãn nhãn trong câu chuyện về hai phù thủy xứ Oz. Sau khi rạn nứt, Elphaba (Cynthia Erivo) bị coi là "phù thủy độc ác" và sống ẩn dật, còn Glinda (Ariana Grande) trở thành biểu tượng của cái thiện tại Thành Phố Ngọc Lục Bảo (Emerald City). Khi một cô gái từ Kansas xuất hiện, những bí mật, xung đột và tình bạn giữa họ bị đẩy đến cao trào. Liệu Elphaba và Glinda có thể vượt qua định kiến để thay đổi số phận Oz?',
                'dao_dien' => 'Michael Chaves',
                'dien_vien' => ' David Corenswet, Rachel Brosnahan',
                'thoi_luong' => 120,
                'ngay_phat_hanh' => '2025-07-05',
                'ngay_ket_thuc' => '2025-07-17',
                'trailer' => 'https://youtu.be/PV9lLq4obkQ',
                'poster' => 'posters/M2.jpg',
                'ngon_ngu' => 'Tiếng Anh',
                'quoc_gia' => 'Mỹ',
                'do_tuoi' => 'T113',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_phim' => 'Thanh Gươm Diệt Quỷ: Vô Hạn Thành',
                'mo_ta' => 'Phim mới Thanh Gươm Diệt Quỷ: Vô Hạn Thành là phần đầu tiên diễn ra khi trận chiến cuối cùng giữa Sát Quỷ Đoàn cùng Muzan và bè lũ bùng nổ tại Vô Hạn Thành.

Kamado Tanjiro gia nhập Sát Quỷ Đoàn sau khi em gái Nezuko bị biến thành quỷ. Trong quá trình trưởng thành, Tanjiro đã chiến đấu với nhiều con quỷ cùng với các đồng đội Agatsuma Zenitsu và Hashibira Inosuke. Hành trình đưa cậu đến với cuộc chiến cùng những kiếm sĩ cấp cao nhất của Sát Quỷ Đoàn - các Trụ Cột - gồm Viêm Trụ Rengoku Kyojuro trên Chuyến Tàu Vô Tận, Âm Trụ Uzui Tengen tại Kỹ Viện Trấn, cũng như Hà Trụ Tokito Muichiro và Luyến Trụ Kanroji Mitsuri tại Làng Thợ Rèn.
Khi các thành viên của Sát Quỷ Đoàn và Trụ Cột tham gia vào chương trình đặc huấn để chuẩn bị cho trận chiến sắp với lũ quỷ, Kibutsuji Muzan xuất hiện tại Dinh thự Ubuyashiki. Khi thủ lĩnh của Sát Quỷ Đoàn gặp nguy hiểm, Tanjiro và các Trụ Cột trở về trụ sở Thế nhưng, Muzan bất ngờ kéo toàn bộ Sát Quỷ Đoàn đến hang ổ cuối cùng của lũ quỷ là Vô Hạn Thành, mở màn cho trận đánh cuối cùng của cả hai phe. 
Phim mới Demon Slayer -Kimetsu no Yaiba- The Movie: Infinity Castle/ Thanh Gươm Diệt Quỷ: Vô Hạn Thành là phần đầu tiên trong bộ ba phim điện ảnh về cuộc chiến bi tráng, đẫm máu và cảm xúc bậc nhất này. Bộ phim sẽ là một trải nghiệm điện ảnh khó quên tại rạp chiếu phim với các fan của Thanh Gươm Diệt Quỷ.',
                'dao_dien' => 'Sotozaki Haruo',
                'dien_vien' => 'Matsuoka Yoshitsugu,David Corenswet, Rachel Brosnahan',
                'thoi_luong' => 180,
                'ngay_phat_hanh' => '2025-07-10',
                'ngay_ket_thuc' => '2025-07-20',
                'trailer' => 'https://youtu.be/U0eSjZtRq8o',
                'poster' => 'posters/thanhguom.jpg',
                'ngon_ngu' => 'Tiếng Nhật',
                'quoc_gia' => 'Nhật ',
                'do_tuoi' => 'T16',
                'trang_thai' => 1,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
        ];

        $chiNhanhs = ChiNhanh::all();
        $raps = RapPhim::all();
        $theLoaiPhims = TheLoaiPhim::pluck('id')->toArray();
        $dinhDangPhims = DinhDangPhim::pluck('id')->toArray();
        $phuDePhims = PhuDePhim::pluck('id')->toArray();

        foreach ($phimData as $phim) {
            $phimId = DB::table('phims')->insertGetId($phim);

            foreach ($chiNhanhs as $chiNhanh) {
                DB::table('phim_chi_nhanhs')->insert([
                    'phim_id' => $phimId,
                    'chi_nhanh_id' => $chiNhanh->id,
                ]);
            }

            foreach ($raps as $rap) {
                DB::table('phim_raps')->insert([
                    'phim_id' => $phimId,
                    'rap_phim_id' => $rap->id,
                ]);
            }

            // $randomTheLoais = Arr::random($theLoaiPhims, rand(1, 3));
            $randomTheLoais = Arr::random($theLoaiPhims, min(count($theLoaiPhims), rand(1, 3)));
            foreach ((array) $randomTheLoais as $theLoaiId) {
                DB::table('phim_the_loais')->insert([
                    'phim_id' => $phimId,
                    'the_loai_phim_id' => $theLoaiId,
                ]);
            }

            // $randomDinhDang = Arr::random($dinhDangPhims, rand(1, 2));
            $randomDinhDang = Arr::random($dinhDangPhims, min(count($dinhDangPhims), rand(1, 2)));
            foreach ((array) $randomDinhDang as $dinhDangId) {
                DB::table('phim_dinh_dangs')->insert([
                    'phim_id' => $phimId,
                    'dinh_dang_phim_id' => $dinhDangId,
                ]);
            }

            // $randomPhuDe = Arr::random($phuDePhims, rand(1, 2));
            $randomPhuDe = Arr::random($phuDePhims, min(count($phuDePhims), rand(1, 2)));
            foreach ((array) $randomPhuDe as $phuDeId) {
                DB::table('phim_phu_des')->insert([
                    'phim_id' => $phimId,
                    'phu_de_phim_id' => $phuDeId,
                ]);
            }
        }
    }
}
