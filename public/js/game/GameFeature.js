/* ================================================================
   GAME FEATURE — Hub 4 Mini-Game Rohani
   Requires: React 18 CDN + Babel Standalone (no import/export)
================================================================ */
const {
  useState,
  useEffect,
  useRef,
  useCallback
} = React;

/* ── PALETTE ───────────────────────────────────────────────── */
const P = {
  night: "#1A1340",
  nightSoft: "#241A57",
  gold: "#F5C451",
  goldSoft: "#FFE08A",
  cream: "#F7F3E8",
  green: "#4ADE80",
  red: "#FF6B6B",
  p1: "#F5C451",
  p2: "#5EE0D0",
  muted: "#A99FD6",
  purple: "#A78BFA",
  orange: "#FF9F8E"
};
const AVC = ["#F5C451", "#5EE0D0", "#FF9F8E", "#A78BFA", "#7DD3FC", "#FCA5D8", "#86EFAC", "#FDBA74"];
const gBtn = {
  padding: "8px 14px",
  borderRadius: 12,
  border: "1px solid rgba(255,255,255,0.14)",
  background: "rgba(255,255,255,0.04)",
  color: "#F7F3E8",
  fontWeight: 700,
  fontSize: 13,
  cursor: "pointer",
  fontFamily: "inherit"
};

/* ── BANK DATA ─────────────────────────────────────────────── */
const BANK_SOAL = [{
  q: "Siapa yang membangun bahtera atas perintah Tuhan?",
  opsi: ["Musa", "Nuh", "Abraham", "Yusuf"],
  benar: 1
}, {
  q: "Di kota manakah Yesus dilahirkan?",
  opsi: ["Nazaret", "Yerusalem", "Betlehem", "Kapernaum"],
  benar: 2
}, {
  q: "Berapa jumlah murid Yesus?",
  opsi: ["10", "12", "7", "40"],
  benar: 1
}, {
  q: "Siapa yang menerima Sepuluh Perintah Allah di Gunung Sinai?",
  opsi: ["Harun", "Yosua", "Musa", "Daud"],
  benar: 2
}, {
  q: "Kitab pertama dalam Alkitab adalah?",
  opsi: ["Keluaran", "Kejadian", "Mazmur", "Yohanes"],
  benar: 1
}, {
  q: "Siapa yang mengalahkan raksasa Goliat?",
  opsi: ["Saul", "Simson", "Daud", "Yonatan"],
  benar: 2
}, {
  q: "Pada hari ke berapa Tuhan beristirahat setelah menciptakan dunia?",
  opsi: ["Keenam", "Ketujuh", "Kelima", "Kedelapan"],
  benar: 1
}, {
  q: "Siapa nabi yang ditelan ikan besar?",
  opsi: ["Yesaya", "Yunus", "Elia", "Daniel"],
  benar: 1
}, {
  q: "Siapa ibu Yesus?",
  opsi: ["Marta", "Maria", "Elisabet", "Hana"],
  benar: 1
}, {
  q: "Mukjizat pertama Yesus mengubah air menjadi?",
  opsi: ["Roti", "Madu", "Anggur", "Minyak"],
  benar: 2
}, {
  q: "Siapa yang menyangkal Yesus tiga kali?",
  opsi: ["Yudas", "Tomas", "Yohanes", "Petrus"],
  benar: 3
}, {
  q: "Taman tempat Adam dan Hawa tinggal disebut?",
  opsi: ["Getsemani", "Eden", "Sinai", "Galilea"],
  benar: 1
}, {
  q: "Siapa yang membaptis Yesus di Sungai Yordan?",
  opsi: ["Petrus", "Andreas", "Yohanes Pembaptis", "Elia"],
  benar: 2
}, {
  q: "Berapa lama bangsa Israel mengembara di padang gurun?",
  opsi: ["7 tahun", "40 tahun", "12 tahun", "70 tahun"],
  benar: 1
}, {
  q: "Siapa yang menjual Yusuf kepada para pedagang?",
  opsi: ["Ayahnya", "Orang Mesir", "Saudara-saudaranya", "Firaun"],
  benar: 2
}, {
  q: "Kitab terakhir dalam Alkitab adalah?",
  opsi: ["Maleakhi", "Wahyu", "Yudas", "Kisah Para Rasul"],
  benar: 1
}, {
  q: "Raja Israel yang terkenal bijaksana dan membangun Bait Suci pertama?",
  opsi: ["Daud", "Saul", "Salomo", "Hizkia"],
  benar: 2
}, {
  q: "Roh Kudus turun atas para murid pada hari?",
  opsi: ["Paskah", "Pentakosta", "Natal", "Sabat"],
  benar: 1
}, {
  q: "Siapa yang berjalan di atas air lalu mulai tenggelam?",
  opsi: ["Yohanes", "Yakobus", "Petrus", "Andreas"],
  benar: 2
}, {
  q: "Berapa banyak kitab dalam Perjanjian Baru?",
  opsi: ["39", "27", "66", "12"],
  benar: 1
}, {
  q: "Ratu yang menyelamatkan bangsanya, namanya menjadi judul kitab?",
  opsi: ["Rut", "Debora", "Ester", "Hana"],
  benar: 2
}, {
  q: "Siapa yang dibangkitkan Yesus dari kematian setelah empat hari?",
  opsi: ["Lazarus", "Nikodemus", "Bartimeus", "Yairus"],
  benar: 0
}, {
  q: "Makanan apa yang Tuhan turunkan dari langit di padang gurun?",
  opsi: ["Roti", "Buah ara", "Manna", "Madu"],
  benar: 2
}, {
  q: "Rasul yang dulu menganiaya orang Kristen lalu menulis banyak surat?",
  opsi: ["Petrus", "Paulus", "Barnabas", "Lukas"],
  benar: 1
}, {
  q: "Sungai tempat bayi Musa dihanyutkan?",
  opsi: ["Yordan", "Nil", "Efrat", "Tigris"],
  benar: 1
}, {
  q: "Siapa istri pertama yang diciptakan Tuhan?",
  opsi: ["Sara", "Hawa", "Rebeka", "Rahel"],
  benar: 1
}, {
  q: "Siapa yang diikat Abraham untuk dipersembahkan di Gunung Moria?",
  opsi: ["Ismael", "Esau", "Ishak", "Yakub"],
  benar: 2
}, {
  q: "Berapa hari Yesus berpuasa di padang gurun?",
  opsi: ["7 hari", "40 hari", "30 hari", "3 hari"],
  benar: 1
}, {
  q: "Siapa yang memimpin bangsa Israel masuk ke Tanah Kanaan setelah Musa wafat?",
  opsi: ["Kaleb", "Yosua", "Gideon", "Simson"],
  benar: 1
}, {
  q: "Nabi mana yang naik ke surga dengan kereta berapi?",
  opsi: ["Elisa", "Elia", "Yesaya", "Yeremia"],
  benar: 1
}, {
  q: "Siapa yang kekuatannya terletak pada rambutnya?",
  opsi: ["Gideon", "Simson", "Boas", "Otniel"],
  benar: 1
}, {
  q: "Berapa jumlah tembok Yerikho runtuh setelah dikelilingi bangsa Israel?",
  opsi: ["Ke-3", "Ke-5", "Ke-7", "Ke-12"],
  benar: 2
}, {
  q: "Siapa yang menafsirkan mimpi Firaun tentang tujuh tahun kelimpahan dan kelaparan?",
  opsi: ["Musa", "Yusuf", "Daniel", "Yakub"],
  benar: 1
}, {
  q: "Kota apa yang dihancurkan Tuhan bersama Gomora karena dosa besar?",
  opsi: ["Sodom", "Niniwe", "Babel", "Tirus"],
  benar: 0
}, {
  q: "Siapa nama istri Lot yang menjadi tiang garam?",
  opsi: ["Tidak disebutkan namanya", "Sara", "Milka", "Naomi"],
  benar: 0
}, {
  q: "Siapa hakim perempuan yang memimpin Israel?",
  opsi: ["Ester", "Debora", "Rut", "Hulda"],
  benar: 1
}, {
  q: "Berapa keping perak Yudas menerima untuk mengkhianati Yesus?",
  opsi: ["10", "20", "30", "50"],
  benar: 2
}, {
  q: "Di bukit apa Yesus disalibkan?",
  opsi: ["Sinai", "Golgota", "Sion", "Karmel"],
  benar: 1
}, {
  q: "Siapa murid yang meragukan kebangkitan Yesus sampai melihat bekas lukanya?",
  opsi: ["Tomas", "Filipus", "Bartolomeus", "Matius"],
  benar: 0
}, {
  q: "Siapa yang menulis sebagian besar kitab Mazmur?",
  opsi: ["Salomo", "Musa", "Daud", "Asaf"],
  benar: 2
}, {
  q: "Nabi mana yang menikahi seorang perempuan sundal atas perintah Tuhan sebagai lambang?",
  opsi: ["Hosea", "Amos", "Mikha", "Zefanya"],
  benar: 0
}, {
  q: "Siapa raja yang memerintahkan pembunuhan bayi-bayi di Betlehem?",
  opsi: ["Herodes", "Pilatus", "Kaisar Agustus", "Ahab"],
  benar: 0
}, {
  q: "Gunung apa tempat Musa melihat Tanah Perjanjian sebelum wafat?",
  opsi: ["Sinai", "Horeb", "Nebo", "Karmel"],
  benar: 2
}, {
  q: "Siapa yang menjadi menantu Musa dan memberi nasihat sistem pengadilan?",
  opsi: ["Yitro", "Harun", "Kaleb", "Hur"],
  benar: 0
}, {
  q: "Kitab apa yang berisi surat cinta/puisi kasih antara mempelai?",
  opsi: ["Amsal", "Pengkhotbah", "Kidung Agung", "Ratapan"],
  benar: 2
}, {
  q: "Siapa nabi yang menantang 450 nabi Baal di Gunung Karmel?",
  opsi: ["Elisa", "Elia", "Yesaya", "Yehezkiel"],
  benar: 1
}, {
  q: "Berapa lama Yunus berada dalam perut ikan?",
  opsi: ["1 hari 1 malam", "3 hari 3 malam", "7 hari", "40 hari"],
  benar: 1
}, {
  q: "Siapa yang menjadi raja pertama bangsa Israel?",
  opsi: ["Daud", "Saul", "Salomo", "Ish-Boset"],
  benar: 1
}, {
  q: "Rasul mana yang dijuluki 'kekasih Yesus' dan menulis Injil keempat?",
  opsi: ["Matius", "Markus", "Lukas", "Yohanes"],
  benar: 3
}, {
  q: "Di manakah Paulus bertobat setelah melihat cahaya dari langit?",
  opsi: ["Yerusalem", "Damaskus", "Antiokhia", "Roma"],
  benar: 1
}];
const BANK_AYAT = [{
  ref: "Yohanes 3:16",
  teks: "Karena begitu besar kasih Allah akan dunia ini sehingga Ia telah mengaruniakan Anak-Nya yang tunggal"
}, {
  ref: "Mazmur 23:1",
  teks: "Tuhan adalah gembalaku takkan kekurangan aku"
}, {
  ref: "Filipi 4:13",
  teks: "Segala perkara dapat kutanggung di dalam Dia yang memberi kekuatan kepadaku"
}, {
  ref: "Yosua 1:9",
  teks: "Kuatkan dan teguhkanlah hatimu sebab Tuhan Allahmu menyertai engkau"
}, {
  ref: "Amsal 3:5",
  teks: "Percayalah kepada Tuhan dengan segenap hatimu dan janganlah bersandar kepada pengertianmu sendiri"
}, {
  ref: "Roma 8:28",
  teks: "Kita tahu sekarang bahwa Allah turut bekerja dalam segala sesuatu untuk mendatangkan kebaikan"
}, {
  ref: "Mazmur 46:1",
  teks: "Allah itu bagi kita tempat perlindungan dan kekuatan sebagai penolong dalam kesesakan"
}, {
  ref: "Yesaya 40:31",
  teks: "Orang yang menanti Tuhan mendapat kekuatan baru mereka seperti rajawali yang naik terbang"
}, {
  ref: "1 Yohanes 4:8",
  teks: "Barangsiapa tidak mengasihi ia tidak mengenal Allah sebab Allah adalah kasih"
}, {
  ref: "Matius 5:9",
  teks: "Berbahagialah orang yang membawa damai karena mereka akan disebut anak-anak Allah"
}, {
  ref: "Mazmur 121:2",
  teks: "Pertolonganku ialah dari Tuhan yang menjadikan langit dan bumi"
}, {
  ref: "Yeremia 29:11",
  teks: "Sebab Aku mengetahui rancangan yang ada pada-Ku yaitu rancangan damai sejahtera bukan kecelakaan"
}, {
  ref: "Amsal 16:3",
  teks: "Serahkanlah perbuatanmu kepada Tuhan maka terlaksanalah segala rencanamu"
}, {
  ref: "Mazmur 27:1",
  teks: "Tuhan adalah terang dan keselamatanku kepada siapakah aku harus takut"
}, {
  ref: "Matius 6:33",
  teks: "Carilah dahulu kerajaan Allah dan kebenarannya maka semuanya akan ditambahkan kepadamu"
}, {
  ref: "Roma 12:2",
  teks: "Janganlah kamu menjadi serupa dengan dunia ini tetapi berubahlah oleh pembaruan budimu"
}, {
  ref: "Galatia 5:22",
  teks: "Buah roh ialah kasih sukacita damai sejahtera kesabaran kemurahan kebaikan kesetiaan"
}, {
  ref: "Mazmur 34:8",
  teks: "Kecaplah dan lihatlah betapa baiknya Tuhan berbahagialah orang yang berlindung pada-Nya"
}, {
  ref: "Ibrani 11:1",
  teks: "Iman adalah dasar dari segala sesuatu yang kita harapkan bukti dari segala sesuatu yang tidak kita lihat"
}, {
  ref: "1 Korintus 13:4",
  teks: "Kasih itu sabar kasih itu murah hati ia tidak cemburu ia tidak memegahkan diri"
}, {
  ref: "Mazmur 119:105",
  teks: "Firman-Mu itu pelita bagi kakiku dan terang bagi jalanku"
}, {
  ref: "Efesus 2:8",
  teks: "Karena kasih karunia kamu diselamatkan oleh iman itu bukan hasil usahamu tetapi pemberian Allah"
}, {
  ref: "Filipi 4:6",
  teks: "Janganlah hendaknya kamu kuatir tentang apapun juga tetapi nyatakanlah dalam segala hal keinginanmu kepada Allah"
}, {
  ref: "Mazmur 37:4",
  teks: "Bergembiralah karena Tuhan maka Ia akan memberikan kepadamu apa yang diinginkan hatimu"
}, {
  ref: "2 Timotius 1:7",
  teks: "Allah memberikan kepada kita roh yang tidak menimbulkan ketakutan tetapi roh yang membangkitkan kekuatan"
}, {
  ref: "Mazmur 1:1",
  teks: "Berbahagialah orang yang tidak berjalan menurut nasihat orang fasik"
}, {
  ref: "Amsal 22:6",
  teks: "Didiklah orang muda menurut jalan yang patut baginya maka pada masa tuanya ia tidak akan menyimpang"
}, {
  ref: "Yakobus 1:5",
  teks: "Apabila di antara kamu ada yang kekurangan hikmat hendaklah ia memintanya kepada Allah"
}, {
  ref: "Kolose 3:23",
  teks: "Apapun juga yang kamu perbuat perbuatlah dengan segenap hatimu seperti untuk Tuhan"
}, {
  ref: "Mazmur 91:1",
  teks: "Orang yang duduk dalam lindungan Yang Mahatinggi akan bermalam dalam naungan Yang Mahakuasa"
}];
const BANK_TOKOH = [{
  jawaban: "Musa",
  clues: ["Dibesarkan di istana Mesir", "Memimpin bangsa Israel keluar dari perbudakan", "Menerima Sepuluh Perintah Allah di Gunung Sinai"],
  salah: ["Abraham", "Elias", "Harun"]
}, {
  jawaban: "Daud",
  clues: ["Mulanya seorang gembala domba", "Mengalahkan raksasa dengan batu dan umban", "Menulis banyak Mazmur dan menjadi raja Israel"],
  salah: ["Goliat", "Yonatan", "Saul"]
}, {
  jawaban: "Yusuf",
  clues: ["Anak kesayangan Yakub dengan jubah istimewa", "Dijual oleh saudara-saudaranya ke Mesir", "Menjadi perdana menteri Mesir setelah menafsirkan mimpi Firaun"],
  salah: ["Yakub", "Benyamin", "Ruben"]
}, {
  jawaban: "Daniel",
  clues: ["Dibuang ke Babel semasa muda", "Dimasukkan ke gua singa karena setia berdoa", "Menafsirkan mimpi dan tulisan di dinding istana raja"],
  salah: ["Sadrakh", "Mesakh", "Abednego"]
}, {
  jawaban: "Ester",
  clues: ["Seorang Yahudi yang menjadi ratu Persia", "Menolong bangsanya dari rencana jahat Haman", "Namanya diabadikan dalam salah satu kitab Alkitab"],
  salah: ["Rut", "Debora", "Hana"]
}, {
  jawaban: "Rut",
  clues: ["Wanita Moab yang setia menemani mertuanya Naomi", "Memungut jelai di ladang Boas", "Menjadi nenek moyang Daud dan Yesus"],
  salah: ["Orpa", "Naomi", "Ester"]
}, {
  jawaban: "Petrus",
  clues: ["Seorang nelayan di Danau Galilea", "Pernah berjalan di atas air menemui Yesus", "Menyangkal Yesus tiga kali sebelum fajar"],
  salah: ["Yohanes", "Andreas", "Yakobus"]
}, {
  jawaban: "Paulus",
  clues: ["Pernah menganiaya orang Kristen dengan giat", "Bertobat setelah melihat cahaya di jalan Damaskus", "Menulis lebih dari separuh surat dalam Perjanjian Baru"],
  salah: ["Barnabas", "Silas", "Lukas"]
}, {
  jawaban: "Nuh",
  clues: ["Hidup 950 tahun lamanya", "Membangun bahtera raksasa atas perintah Tuhan", "Menyelamatkan keluarganya dan hewan dari air bah"],
  salah: ["Abraham", "Lot", "Sem"]
}, {
  jawaban: "Yunus",
  clues: ["Melarikan diri ke Tarsis menghindari tugas Tuhan", "Ditelan seekor ikan besar selama tiga hari tiga malam", "Memberitakan pertobatan kepada kota Niniwe"],
  salah: ["Elia", "Yesaya", "Mikha"]
}, {
  jawaban: "Abraham",
  clues: ["Dipanggil Tuhan meninggalkan tanah kelahirannya", "Disebut bapak segala bangsa yang percaya", "Bersedia mempersembahkan anaknya Ishak di Gunung Moria"],
  salah: ["Ishak", "Yakub", "Lot"]
}, {
  jawaban: "Simson",
  clues: ["Kekuatannya terletak pada rambutnya yang panjang", "Jatuh cinta pada Delila yang mengkhianatinya", "Merobohkan kuil orang Filistin dengan kekuatan terakhirnya"],
  salah: ["Gideon", "Boas", "Otniel"]
}, {
  jawaban: "Salomo",
  clues: ["Anak Daud yang terkenal karena hikmatnya", "Membangun Bait Suci pertama di Yerusalem", "Menulis kitab Amsal dan Kidung Agung"],
  salah: ["Rehabeam", "Daud", "Hizkia"]
}, {
  jawaban: "Elia",
  clues: ["Menantang para nabi Baal di Gunung Karmel", "Diberi makan burung gagak di tepi sungai Kerit", "Naik ke surga dengan kereta dan kuda berapi"],
  salah: ["Elisa", "Yesaya", "Yeremia"]
}, {
  jawaban: "Yakub",
  clues: ["Adik kembar Esau yang licik merebut berkat sulung", "Bermimpi tentang tangga yang sampai ke langit", "Namanya diubah menjadi Israel setelah bergumul dengan malaikat"],
  salah: ["Esau", "Ishak", "Laban"]
}, {
  jawaban: "Yohanes Pembaptis",
  clues: ["Anak Zakharia dan Elisabet di masa tuanya", "Hidup di padang gurun memakan belalang dan madu hutan", "Membaptis Yesus di Sungai Yordan"],
  salah: ["Yohanes Rasul", "Elia", "Yakobus"]
}, {
  jawaban: "Maria",
  clues: ["Seorang perawan dari Nazaret", "Menerima kabar dari malaikat Gabriel bahwa ia akan mengandung", "Menjadi ibu dari Yesus Kristus"],
  salah: ["Marta", "Elisabet", "Maria Magdalena"]
}, {
  jawaban: "Yudas Iskariot",
  clues: ["Salah satu dari kedua belas murid Yesus", "Memegang kas kelompok murid", "Mengkhianati Yesus dengan sebuah ciuman demi tiga puluh keping perak"],
  salah: ["Tomas", "Simon Zelot", "Matius"]
}, {
  jawaban: "Gideon",
  clues: ["Awalnya takut dan meminta tanda bulu domba dari Tuhan", "Memimpin hanya 300 orang melawan tentara Midian", "Menjadi salah satu hakim Israel"],
  salah: ["Simson", "Barak", "Yefta"]
}, {
  jawaban: "Zakheus",
  clues: ["Seorang kepala pemungut cukai yang kaya", "Bertubuh pendek sehingga memanjat pohon ara", "Bertobat dan mengembalikan hartanya empat kali lipat setelah bertemu Yesus"],
  salah: ["Matius", "Simon", "Bartimeus"]
}];
const BANK_KARTU = [{
  a: "Nuh",
  b: "Membangun bahtera dari kayu gofir"
}, {
  a: "Musa",
  b: "Membelah Laut Merah dengan tongkat"
}, {
  a: "Daud",
  b: "Mengalahkan Goliat dengan batu"
}, {
  a: "Yusuf",
  b: "Jubah indah berwarna-warni"
}, {
  a: "Daniel",
  b: "Selamat dari gua singa"
}, {
  a: "Simson",
  b: "Kekuatan terletak pada rambutnya"
}, {
  a: "Elia",
  b: "Naik ke surga dengan kereta api"
}, {
  a: "Yunus",
  b: "Tiga hari dalam perut ikan"
}, {
  a: "Maria",
  b: "Ibu dari Yesus Kristus"
}, {
  a: "Petrus",
  b: "Kunci kerajaan surga"
}, {
  a: "Paulus",
  b: "Bertobat di jalan Damaskus"
}, {
  a: "Abraham",
  b: "Bapak segala bangsa"
}, {
  a: "Salomo",
  b: "Membangun Bait Suci pertama"
}, {
  a: "Ester",
  b: "Menyelamatkan bangsa Yahudi"
}, {
  a: "Rut",
  b: "Setia mengikuti mertua Naomi"
}, {
  a: "Yosua",
  b: "Memimpin Israel masuk Kanaan"
}, {
  a: "Adam",
  b: "Manusia pertama yang diciptakan"
}, {
  a: "Hawa",
  b: "Wanita pertama, tergoda ulat"
}, {
  a: "Ishak",
  b: "Anak perjanjian Abraham dan Sara"
}, {
  a: "Yakub",
  b: "Bergumul dengan malaikat semalaman"
}, {
  a: "Gideon",
  b: "Menang perang dengan 300 orang"
}, {
  a: "Debora",
  b: "Hakim perempuan Israel"
}, {
  a: "Zakheus",
  b: "Memanjat pohon ara demi melihat Yesus"
}, {
  a: "Yohanes Pembaptis",
  b: "Membaptis Yesus di Sungai Yordan"
}, {
  a: "Tomas",
  b: "Meragukan kebangkitan Yesus"
}, {
  a: "Lazarus",
  b: "Dibangkitkan setelah empat hari mati"
}, {
  a: "Yudas Iskariot",
  b: "Mengkhianati Yesus demi uang"
}, {
  a: "Hizkia",
  b: "Raja yang sembuh dari sakit parah"
}, {
  a: "Yesaya",
  b: "Menubuatkan kelahiran Mesias"
}, {
  a: "Yeremia",
  b: "Dijuluki nabi yang menangis"
}, {
  a: "Yohanes Rasul",
  b: "Menulis Kitab Wahyu di Pulau Patmos"
}, {
  a: "Timotius",
  b: "Murid muda yang dibina Rasul Paulus"
}];
function getLeaderboard() {
  const raw = window.__GAME_LEADERBOARD__;
  if (!raw || !raw.length) return [];
  return raw;
}

/* Gabungkan bank soal bawaan (hardcoded di atas) dengan soal tambahan yang
   ditambahkan superadmin lewat halaman admin (tabel game_questions, dikirim
   via window.__GAME_QUESTIONS__ dari GameController). Kalau tidak ada soal
   custom untuk tipe game tsb, hasilnya sama seperti bank bawaan saja. */
function bankGabungan(bawaan, gameType) {
  const custom = (window.__GAME_QUESTIONS__ || {})[gameType];
  return custom && custom.length ? [...bawaan, ...custom] : bawaan;
}
function bankSoal() {
  return bankGabungan(BANK_SOAL, "kuis");
}
function bankAyat() {
  return bankGabungan(BANK_AYAT, "susun");
}
function bankTokoh() {
  return bankGabungan(BANK_TOKOH, "tebak");
}
function bankKartu() {
  return bankGabungan(BANK_KARTU, "memory");
}
const GAME_DEFS = [{
  id: "kuis",
  ikon: "⚡",
  judul: "Kuis Adu Cepat",
  desc: "Trivia Alkitab — jawab tercepat",
  warna: P.gold
}, {
  id: "susun",
  ikon: "📖",
  judul: "Susun Ayat",
  desc: "Acak kata — rangkai ayat suci",
  warna: P.p2
}, {
  id: "tebak",
  ikon: "🔍",
  judul: "Tebak Tokoh",
  desc: "Clue bertahap — siapa aku?",
  warna: P.purple
}, {
  id: "memory",
  ikon: "🃏",
  judul: "Memory Match",
  desc: "Cocokkan kartu — uji ingatan",
  warna: P.orange
}];

/* ── UTILS ─────────────────────────────────────────────────── */
function shuffle(arr) {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = 0 | Math.random() * (i + 1);
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

/* RNG berbasis seed (mulberry32) supaya kedua pemain di sesi online yang
   sama mendapat urutan acak IDENTIK — tanpa ini tiap klien memakai
   Math.random() sendiri-sendiri sehingga soal & jawaban berbeda. */
function buatRng(seed) {
  let s = 0;
  for (let i = 0; i < seed.length; i++) s = s * 31 + seed.charCodeAt(i) >>> 0;
  return function () {
    s |= 0;
    s = s + 0x6D2B79F5 | 0;
    let t = Math.imul(s ^ s >>> 15, 1 | s);
    t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t;
    return ((t ^ t >>> 14) >>> 0) / 4294967296;
  };
}
function shuffleSeed(arr, rng) {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = 0 | rng() * (i + 1);
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

/* Saring bank sebelum seeded-shuffle supaya soal yang baru dipakai tenant ini
   beberapa match terakhir (avoid_keys dari server, lihat GameSessionController)
   tidak muncul lagi dulu. Kalau kandidat setelah disaring kurang dari
   kebutuhan (bank kecil dibanding hindariKeys), fallback ke bank penuh —
   lebih baik ada sedikit pengulangan daripada macet karena kandidat < n. */
function saringHindari(bank, idFn, hindariKeys) {
  if (!hindariKeys || !hindariKeys.length) return bank;
  const set = new Set(hindariKeys);
  const segar = bank.filter(x => !set.has(idFn(x)));
  return segar;
}
function siapkanSoalSeed(n, seed, hindariKeys) {
  const rng = buatRng(seed);
  const bank = bankSoal();
  let kandidat = saringHindari(bank, s => s.q, hindariKeys);
  if (kandidat.length < n) kandidat = bank;
  const dipilih = shuffleSeed(kandidat, rng).slice(0, n);
  return dipilih.map(s => {
    const b = s.opsi[s.benar];
    const o = shuffleSeed(s.opsi, rng);
    return {
      q: s.q,
      opsi: o,
      benar: o.indexOf(b)
    };
  });
}
function siapkanAyatSeed(n, seed, hindariKeys) {
  const rng = buatRng(seed);
  const bank = bankAyat();
  let kandidat = saringHindari(bank, a => a.ref, hindariKeys);
  if (kandidat.length < n) kandidat = bank;
  const dipilih = shuffleSeed(kandidat, rng).slice(0, n);
  return dipilih.map(a => {
    const kata = a.teks.split(" ");
    return {
      ref: a.ref,
      kata,
      acak: shuffleSeed([...kata], rng)
    };
  });
}
function siapkanTokohSeed(n, seed, hindariKeys) {
  const rng = buatRng(seed);
  const bank = bankTokoh();
  let kandidat = saringHindari(bank, t => t.jawaban, hindariKeys);
  if (kandidat.length < n) kandidat = bank;
  const dipilih = shuffleSeed(kandidat, rng).slice(0, n);
  return dipilih.map(t => {
    let op = shuffleSeed([t.jawaban, ...t.salah], rng).slice(0, 4);
    if (!op.includes(t.jawaban)) op[0] = t.jawaban;
    return {
      ...t,
      opsi: shuffleSeed(op, rng)
    };
  });
}
function siapkanKartuSeed(n = 8, seed, hindariKeys) {
  const rng = buatRng(seed);
  const bank = bankKartu();
  let kandidat = saringHindari(bank, x => x.a, hindariKeys);
  if (kandidat.length < n) kandidat = bank;
  const p = shuffleSeed(kandidat, rng).slice(0, n);
  return shuffleSeed([...p.map((x, i) => ({
    id: i * 2,
    pair: i,
    isi: x.a
  })), ...p.map((x, i) => ({
    id: i * 2 + 1,
    pair: i,
    isi: x.b
  }))], rng);
}

/* Ekstrak question_key dari hasil siapkanXSeed, untuk dikirim ke server
   (POST /game/record-questions) supaya tercatat sebagai "baru dipakai". */
function keyDariSoal(soal) {
  return soal.map(s => s.q);
}
function keyDariAyat(ayat) {
  return ayat.map(a => a.ref);
}
function keyDariTokoh(tokoh) {
  return tokoh.map(t => t.jawaban);
}
// cards (hasil siapkanKartuSeed) berisi 2 entri per pasang: id genap berasal
// dari sisi "a" (x.a — inilah idFn yang dipakai saringHindari(bankKartu,...)),
// id ganjil dari sisi "b". Ambil isi sisi "a" per pasang sebagai key,
// supaya konsisten dengan key yang dipakai server saat menyaring bank.
function keyDariKartu(cards) {
  const seen = new Set();
  const keys = [];
  cards.forEach(c => {
    if (c.id % 2 === 0 && !seen.has(c.pair)) {
      seen.add(c.pair);
      keys.push(c.isi);
    }
  });
  return keys;
}
function inisial(n) {
  return n.split(" ").map(w => w[0]).join("").slice(0, 2).toUpperCase();
}
function warnaDari(n) {
  let h = 0;
  for (let i = 0; i < n.length; i++) h = n.charCodeAt(i) + ((h << 5) - h);
  return AVC[Math.abs(h) % AVC.length];
}

/* Anti-pengulangan: catat item yang baru dipakai (per kategori) di localStorage,
   dan pada pengambilan berikutnya utamakan item yang BELUM ada di riwayat itu
   dulu sebelum terpaksa mengulang. Riwayat dibatasi supaya begitu bank sudah
   "habis dijelajahi", item lama otomatis boleh muncul lagi (bukan diblokir permanen). */
function ambilRiwayat(key) {
  try {
    return JSON.parse(localStorage.getItem("gf_riwayat_" + key) || "[]");
  } catch (e) {
    return [];
  }
}
function simpanRiwayat(key, ids) {
  try {
    localStorage.setItem("gf_riwayat_" + key, JSON.stringify(ids.slice(-200)));
  } catch (e) {/* localStorage penuh/nonaktif, abaikan */}
}
function pilihSegar(bank, n, key, idFn) {
  const riwayat = new Set(ambilRiwayat(key));
  const segar = bank.filter(x => !riwayat.has(idFn(x)));
  const kandidat = segar.length >= n ? segar : bank; // riwayat penuh -> reset, boleh ulang dari semua
  const pilihan = shuffle(kandidat).slice(0, Math.min(n, bank.length));
  const riwayatBaru = [...ambilRiwayat(key), ...pilihan.map(idFn)];
  simpanRiwayat(key, riwayatBaru);
  return pilihan;
}
function siapkanSoal(n) {
  return pilihSegar(bankSoal(), n, "soal", s => s.q).map(s => {
    const b = s.opsi[s.benar];
    const o = shuffle(s.opsi);
    return {
      q: s.q,
      opsi: o,
      benar: o.indexOf(b)
    };
  });
}
function siapkanAyat(n) {
  return pilihSegar(bankAyat(), n, "ayat", a => a.ref).map(a => {
    const kata = a.teks.split(" ");
    return {
      ref: a.ref,
      kata,
      acak: shuffle([...kata])
    };
  });
}
function siapkanTokoh(n) {
  return pilihSegar(bankTokoh(), n, "tokoh", t => t.jawaban).map(t => {
    let op = shuffle([t.jawaban, ...t.salah]).slice(0, 4);
    if (!op.includes(t.jawaban)) op[0] = t.jawaban;
    return {
      ...t,
      opsi: shuffle(op)
    };
  });
}
function siapkanKartu(n = 8) {
  const p = pilihSegar(bankKartu(), n, "kartu", x => x.a);
  return shuffle([...p.map((x, i) => ({
    id: i * 2,
    pair: i,
    isi: x.a
  })), ...p.map((x, i) => ({
    id: i * 2 + 1,
    pair: i,
    isi: x.b
  }))]);
}
function getMembers() {
  const raw = window.__GAME_MEMBERS__;
  if (!raw || !raw.length) return [];
  return raw;
}

/* ── GLOBAL STYLES ─────────────────────────────────────────── */
const GStyles = () => /*#__PURE__*/React.createElement("style", null, `
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap');
    @keyframes gf-pop  {0%{transform:scale(.93);opacity:0}100%{transform:scale(1);opacity:1}}
    @keyframes gf-rise {0%{transform:translateY(16px);opacity:0}100%{transform:translateY(0);opacity:1}}
    @keyframes gf-shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}
    @keyframes gf-blink{0%,100%{opacity:.3}50%{opacity:1}}
    @keyframes gf-flip {0%{transform:rotateY(0)}100%{transform:rotateY(180deg)}}
    @keyframes gf-float-emoji{0%{transform:translateY(0) scale(.6);opacity:0}15%{transform:translateY(-10px) scale(1.1);opacity:1}100%{transform:translateY(-160px) scale(1);opacity:0}}
    .gf-pop  {animation:gf-pop  .28s ease both}
    .gf-rise {animation:gf-rise .32s ease both}
    .gf-shake{animation:gf-shake .3s ease}
    .gf-float-emoji{animation:gf-float-emoji 1.8s ease-out both}
    .gf-btn  {transition:transform .1s ease,filter .15s ease;font-family:inherit;}
    .gf-btn:active{transform:scale(.96)}
    .gf-btn:focus-visible{outline:3px solid #FFE08A;outline-offset:2px}
    .gf-card-wrap{perspective:800px}
    .gf-card-inner{position:relative;width:100%;height:100%;transform-style:preserve-3d;transition:transform .45s ease}
    .gf-card-inner.flipped{transform:rotateY(180deg)}
    .gf-card-face{position:absolute;inset:0;backface-visibility:hidden;border-radius:12px;display:grid;place-items:center;padding:6px;text-align:center;}
    .gf-card-back{transform:rotateY(180deg)}
    @media(prefers-reduced-motion:reduce){.gf-pop,.gf-rise,.gf-shake,.gf-btn,.gf-float-emoji{animation:none!important;transition:none!important}}
  `);

/* ── SHARED COMPONENTS ──────────────────────────────────────── */
function Avatar({
  nama,
  size = 44,
  ring
}) {
  const w = warnaDari(nama);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      width: size,
      height: size,
      borderRadius: "50%",
      background: `linear-gradient(135deg,${w},${w}aa)`,
      display: "grid",
      placeItems: "center",
      color: "#1A1340",
      fontWeight: 800,
      fontSize: size * .36,
      flexShrink: 0,
      border: ring ? `2.5px solid ${ring}` : "none",
      fontFamily: "'Bricolage Grotesque',sans-serif"
    }
  }, inisial(nama));
}
function TimerRing({
  ratio,
  danger,
  size = 52
}) {
  const r = size / 2 - 5,
    c = 2 * Math.PI * r;
  return /*#__PURE__*/React.createElement("svg", {
    width: size,
    height: size,
    style: {
      transform: "rotate(-90deg)"
    }
  }, /*#__PURE__*/React.createElement("circle", {
    cx: size / 2,
    cy: size / 2,
    r: r,
    fill: "none",
    stroke: "rgba(255,255,255,0.12)",
    strokeWidth: "5"
  }), /*#__PURE__*/React.createElement("circle", {
    cx: size / 2,
    cy: size / 2,
    r: r,
    fill: "none",
    stroke: danger ? P.red : P.gold,
    strokeWidth: "5",
    strokeLinecap: "round",
    strokeDasharray: c,
    strokeDashoffset: c * (1 - ratio),
    style: {
      transition: "stroke-dashoffset .25s linear,stroke .3s ease"
    }
  }));
}
function TopBar({
  onBack,
  title,
  subtitle
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      marginBottom: 4
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onBack,
    className: "gf-btn",
    style: {
      ...gBtn,
      padding: "8px 12px"
    }
  }, "← Kembali"), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 19,
      color: P.cream
    }
  }, title), subtitle && /*#__PURE__*/React.createElement("div", {
    style: {
      color: P.muted,
      fontSize: 12.5,
      fontWeight: 600
    }
  }, subtitle)));
}
function Pill({
  color,
  label,
  dim
}) {
  return /*#__PURE__*/React.createElement("span", {
    style: {
      padding: "5px 12px",
      borderRadius: 99,
      background: `${color}1f`,
      border: `1px solid ${color}55`,
      color: dim ? P.muted : color,
      fontWeight: 800,
      fontSize: 13
    }
  }, label);
}
function OptBtn({
  text,
  idx,
  state,
  onClick,
  disabled,
  delay = 0
}) {
  const map = {
    idle: {
      bg: "rgba(255,255,255,0.05)",
      bd: "rgba(255,255,255,0.12)",
      fg: P.cream,
      badge: "rgba(255,255,255,0.1)"
    },
    benar: {
      bg: `${P.green}22`,
      bd: P.green,
      fg: "#D9FBE6",
      badge: P.green
    },
    salah: {
      bg: `${P.red}1f`,
      bd: P.red,
      fg: "#FFE0E0",
      badge: P.red
    },
    redup: {
      bg: "rgba(255,255,255,0.03)",
      bd: "rgba(255,255,255,0.06)",
      fg: "rgba(247,243,232,0.35)",
      badge: "rgba(255,255,255,0.05)"
    }
  };
  const s = map[state] || map.idle;
  const L = ["A", "B", "C", "D"][idx];
  return /*#__PURE__*/React.createElement("button", {
    onClick: onClick,
    disabled: disabled,
    className: "gf-btn gf-rise",
    style: {
      display: "flex",
      alignItems: "center",
      gap: 13,
      padding: "14px 16px",
      borderRadius: 16,
      border: `1.5px solid ${s.bd}`,
      background: s.bg,
      color: s.fg,
      cursor: disabled ? "default" : "pointer",
      fontWeight: 700,
      fontSize: 15,
      textAlign: "left",
      width: "100%",
      animationDelay: `${delay}s`
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 28,
      height: 28,
      borderRadius: 9,
      background: s.badge,
      display: "grid",
      placeItems: "center",
      fontSize: 13,
      fontWeight: 800,
      flexShrink: 0,
      color: state === "idle" ? P.gold : "#1A1340"
    }
  }, state === "benar" ? "✓" : state === "salah" ? "✗" : L), /*#__PURE__*/React.createElement("span", {
    style: {
      flex: 1
    }
  }, text));
}
function ScorePill({
  name,
  val,
  color,
  win
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      padding: "14px 12px",
      borderRadius: 18,
      background: win ? `${color}1a` : "rgba(255,255,255,0.04)",
      border: `1.5px solid ${win ? color : "rgba(255,255,255,0.1)"}`,
      maxWidth: 150,
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12.5,
      fontWeight: 700,
      color: P.muted
    }
  }, name), /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 32,
      color,
      lineHeight: 1.1
    }
  }, val), win && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 18
    }
  }, "🏆"));
}
function Hasil({
  judul,
  skor,
  baris,
  custom,
  accent,
  onExit
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "40px 24px",
      maxWidth: 460,
      margin: "0 auto",
      textAlign: "center",
      display: "flex",
      flexDirection: "column",
      minHeight: 480,
      justifyContent: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "gf-pop"
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 72,
      height: 72,
      margin: "0 auto",
      borderRadius: 24,
      background: `linear-gradient(135deg,${accent},${accent}99)`,
      display: "grid",
      placeItems: "center",
      boxShadow: `0 12px 40px ${accent}55`
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 32
    }
  }, "⭐")), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 26,
      margin: "18px 0 4px",
      color: P.cream
    }
  }, judul)), skor != null && /*#__PURE__*/React.createElement("div", {
    className: "gf-rise",
    style: {
      animationDelay: ".1s"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      color: P.muted,
      fontWeight: 700,
      letterSpacing: 1
    }
  }, "SKOR AKHIR"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 54,
      color: accent,
      lineHeight: 1,
      margin: "4px 0 20px"
    }
  }, skor)), custom, baris && /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 9,
      marginTop: 10
    }
  }, baris.map((b, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      justifyContent: "space-between",
      padding: "12px 16px",
      borderRadius: 14,
      background: "rgba(255,255,255,0.04)",
      border: "1px solid rgba(255,255,255,0.08)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: P.muted,
      fontWeight: 600,
      fontSize: 14
    }
  }, b.label), /*#__PURE__*/React.createElement("span", {
    style: {
      fontWeight: 800,
      fontSize: 15,
      color: P.cream
    }
  }, b.val)))), /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: {
      marginTop: 28,
      padding: 16,
      borderRadius: 16,
      border: "none",
      background: `linear-gradient(135deg,${accent},${accent}cc)`,
      color: "#1A1340",
      fontWeight: 800,
      fontSize: 16,
      cursor: "pointer",
      fontFamily: "'Bricolage Grotesque',sans-serif"
    }
  }, "Kembali ke Menu"));
}

/* ── SHARED SCREENS ─────────────────────────────────────────── */
function PilihCara({
  game,
  onBack,
  onPick
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "24px 20px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement(TopBar, {
    onBack: onBack,
    title: "Cara Bermain",
    subtitle: `${game.judul} — pilih mode`
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 12,
      marginTop: 20
    }
  }, [{
    id: "solo",
    ikon: "👤",
    judul: "Main Solo",
    desc: "Lawan waktu, kejar skor & streak"
  }, {
    id: "tatap",
    ikon: "📱",
    judul: "Hadap-hadapan",
    desc: "Satu HP berdua, layar terbagi 2"
  }, {
    id: "online",
    ikon: "🌐",
    judul: "HP Masing-masing",
    desc: "Beda HP, main bareng secara online"
  }].map((m, i) => {
    const [h, setH] = useState(false);
    return /*#__PURE__*/React.createElement("button", {
      key: m.id,
      onClick: () => onPick(m.id),
      onMouseEnter: () => setH(true),
      onMouseLeave: () => setH(false),
      className: "gf-btn gf-rise",
      style: {
        display: "flex",
        alignItems: "center",
        gap: 14,
        padding: 18,
        borderRadius: 20,
        border: `1px solid ${h ? game.warna : "rgba(255,255,255,0.1)"}`,
        background: h ? "rgba(255,255,255,0.06)" : "rgba(255,255,255,0.03)",
        cursor: "pointer",
        textAlign: "left",
        color: P.cream,
        animationDelay: `${i * .06}s`
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        width: 50,
        height: 50,
        borderRadius: 16,
        background: `${game.warna}22`,
        display: "grid",
        placeItems: "center",
        fontSize: 22,
        flexShrink: 0
      }
    }, m.ikon), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontFamily: "'Bricolage Grotesque',sans-serif",
        fontWeight: 800,
        fontSize: 17
      }
    }, m.judul), /*#__PURE__*/React.createElement("div", {
      style: {
        color: P.muted,
        fontSize: 13,
        fontWeight: 600,
        marginTop: 2
      }
    }, m.desc)), /*#__PURE__*/React.createElement("span", {
      style: {
        color: game.warna,
        fontSize: 18
      }
    }, "›"));
  })));
}
const MEMORY_LEVELS = [{
  id: 1,
  judul: "Level 1 — Pemula",
  desc: "Grid 4x4 · 8 pasang kartu",
  ikon: "🟢"
}, {
  id: 2,
  judul: "Level 2 — Menengah",
  desc: "Grid 8x8 · 32 pasang kartu",
  ikon: "🟡"
}, {
  id: 3,
  judul: "Level 3 — Sulit",
  desc: "Grid 8x8 · kartu diacak ulang tiap 3 giliran",
  ikon: "🔴"
}];
function PilihLevelMemory({
  onBack,
  onPick
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "24px 20px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement(TopBar, {
    onBack: onBack,
    title: "Pilih Level",
    subtitle: "Memory Match — makin tinggi makin menantang"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 12,
      marginTop: 20
    }
  }, MEMORY_LEVELS.map((lv, i) => {
    const [h, setH] = useState(false);
    return /*#__PURE__*/React.createElement("button", {
      key: lv.id,
      onClick: () => onPick(lv.id),
      onMouseEnter: () => setH(true),
      onMouseLeave: () => setH(false),
      className: "gf-btn gf-rise",
      style: {
        display: "flex",
        alignItems: "center",
        gap: 14,
        padding: 18,
        borderRadius: 20,
        border: `1px solid ${h ? P.orange : "rgba(255,255,255,0.1)"}`,
        background: h ? "rgba(255,255,255,0.06)" : "rgba(255,255,255,0.03)",
        cursor: "pointer",
        textAlign: "left",
        color: P.cream,
        animationDelay: `${i * .06}s`
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        width: 50,
        height: 50,
        borderRadius: 16,
        background: `${P.orange}22`,
        display: "grid",
        placeItems: "center",
        fontSize: 22,
        flexShrink: 0
      }
    }, lv.ikon), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontFamily: "'Bricolage Grotesque',sans-serif",
        fontWeight: 800,
        fontSize: 17
      }
    }, lv.judul), /*#__PURE__*/React.createElement("div", {
      style: {
        color: P.muted,
        fontSize: 13,
        fontWeight: 600,
        marginTop: 2
      }
    }, lv.desc)), /*#__PURE__*/React.createElement("span", {
      style: {
        color: P.orange,
        fontSize: 18
      }
    }, "›"));
  })));
}

/* Untuk mode "tatap" (satu HP, 2 pemain) tetap pilih 1 lawan seperti biasa
   (klik langsung). Untuk mode "online" bisa pilih 1-3 lawan sekaligus
   (checkbox + tombol lanjut), supaya total sampai 4 pemain per sesi. */
function PilihLawan({
  game,
  mode,
  onBack,
  onPick
}) {
  const [cari, setCari] = useState("");
  const [members, setMembers] = useState(getMembers());
  const [dipilih, setDipilih] = useState([]);
  useEffect(() => {
    let batal = false;
    const muat = () => apiGet("/game/members").then(data => {
      if (!batal) setMembers(data);
    }).catch(() => {});
    const id = setInterval(muat, 15000);
    return () => {
      batal = true;
      clearInterval(id);
    };
  }, []);
  const list = members.filter(m => m.nama.toLowerCase().includes(cari.toLowerCase())).sort((a, b) => b.online - a.online);
  const multi = mode === "online";
  const toggle = m => {
    setDipilih(d => d.some(x => x.id === m.id) ? d.filter(x => x.id !== m.id) : d.length < 3 ? [...d, m] : d);
  };
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "24px 20px",
      maxWidth: 460,
      margin: "0 auto",
      paddingBottom: multi ? 96 : 24
    }
  }, /*#__PURE__*/React.createElement(TopBar, {
    onBack: onBack,
    title: "Pilih Lawan",
    subtitle: multi ? "Hanya anggota online & tidak sedang main bisa ditantang · maks 3 lawan" : "Pilih lawan bermain"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      marginTop: 14,
      marginBottom: 12
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 14,
      top: 14,
      fontSize: 16
    }
  }, "🔍"), /*#__PURE__*/React.createElement("input", {
    value: cari,
    onChange: e => setCari(e.target.value),
    placeholder: "Cari anggota…",
    style: {
      width: "100%",
      boxSizing: "border-box",
      padding: "12px 14px 12px 40px",
      borderRadius: 14,
      border: "1px solid rgba(255,255,255,0.12)",
      background: "rgba(255,255,255,0.04)",
      color: P.cream,
      fontFamily: "inherit",
      fontWeight: 600,
      fontSize: 14.5,
      outline: "none"
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 8
    }
  }, list.map((m, i) => {
    const bisa = (mode !== "online" || m.online) && !m.sedangMain;
    const aktif = dipilih.some(x => x.id === m.id);
    const handleClick = () => {
      if (!bisa) return;
      if (multi) toggle(m);else onPick(m);
    };
    const keterangan = m.sedangMain ? "Sedang main 🎮" : m.online ? "Online" : "Offline";
    return /*#__PURE__*/React.createElement("button", {
      key: m.id || i,
      disabled: !bisa,
      onClick: handleClick,
      className: "gf-btn gf-rise",
      style: {
        display: "flex",
        alignItems: "center",
        gap: 12,
        padding: 13,
        borderRadius: 16,
        border: `1px solid ${aktif ? game.warna : "rgba(255,255,255,0.09)"}`,
        background: aktif ? `${game.warna}14` : "rgba(255,255,255,0.03)",
        cursor: bisa ? "pointer" : "default",
        color: P.cream,
        textAlign: "left",
        opacity: bisa ? 1 : .4,
        animationDelay: `${i * .04}s`
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        position: "relative"
      }
    }, /*#__PURE__*/React.createElement(Avatar, {
      nama: m.nama
    }), /*#__PURE__*/React.createElement("span", {
      style: {
        position: "absolute",
        right: -1,
        bottom: -1,
        width: 11,
        height: 11,
        borderRadius: 99,
        background: m.sedangMain ? P.orange : m.online ? P.green : "#6B6391",
        border: `2px solid ${P.night}`
      }
    })), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontWeight: 800,
        fontSize: 15
      }
    }, m.nama), /*#__PURE__*/React.createElement("div", {
      style: {
        color: m.sedangMain ? P.orange : P.muted,
        fontSize: 12,
        fontWeight: 600
      }
    }, keterangan, " · ", m.menang || 0, "M/", m.kalah || 0, "K")), bisa && (multi ? /*#__PURE__*/React.createElement("span", {
      style: {
        width: 24,
        height: 24,
        borderRadius: 8,
        border: `1.5px solid ${aktif ? game.warna : "rgba(255,255,255,0.25)"}`,
        background: aktif ? game.warna : "transparent",
        display: "grid",
        placeItems: "center",
        fontSize: 13,
        fontWeight: 800,
        color: "#1A1340",
        flexShrink: 0
      }
    }, aktif ? "✓" : "") : /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 12,
        fontWeight: 800,
        color: "#1A1340",
        background: game.warna,
        padding: "6px 14px",
        borderRadius: 99
      }
    }, "Pilih")));
  }), !list.length && /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center",
      color: P.muted,
      fontWeight: 600,
      padding: 30
    }
  }, "Tidak ada anggota ditemukan.")), multi && /*#__PURE__*/React.createElement("div", {
    style: {
      position: "fixed",
      bottom: "calc(var(--nav-h, 90px) + 12px)",
      left: "50%",
      transform: "translateX(-50%)",
      width: "calc(100% - 32px)",
      maxWidth: 440,
      zIndex: 50
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => dipilih.length && onPick(dipilih),
    disabled: !dipilih.length,
    className: "gf-btn",
    style: {
      width: "100%",
      padding: "14px",
      borderRadius: 16,
      border: "none",
      background: dipilih.length ? game.warna : "rgba(255,255,255,0.1)",
      color: dipilih.length ? "#1A1340" : P.muted,
      fontWeight: 800,
      fontSize: 15,
      cursor: dipilih.length ? "pointer" : "default",
      boxShadow: dipilih.length ? "0 8px 24px rgba(0,0,0,0.4)" : "none"
    }
  }, dipilih.length ? `Tantang ${dipilih.length} orang →` : "Pilih minimal 1 lawan")));
}

/* ── PUSHER HELPER ───────────────────────────────────────────── */
function getPusher() {
  if (window.__PUSHER_INSTANCE__) return window.__PUSHER_INSTANCE__;
  const cfg = window.__PUSHER_CONFIG__ || {};
  if (!cfg.key) {
    console.error("[Game] PUSHER_APP_KEY tidak terkonfigurasi — mode online tidak akan realtime.");
    return null;
  }
  const p = new window.Pusher(cfg.key, {
    cluster: cfg.cluster || "ap1",
    authEndpoint: "/broadcasting/auth",
    auth: {
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || ""
      }
    }
  });
  p.connection.bind("error", e => console.error("[Game] Pusher connection error:", e));
  p.connection.bind("state_change", s => console.log("[Game] Pusher state:", s.previous, "→", s.current));
  window.__PUSHER_INSTANCE__ = p;
  return window.__PUSHER_INSTANCE__;
}
async function apiPost(url, data) {
  const token = document.querySelector('meta[name="csrf-token"]')?.content || "";
  const r = await fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": token,
      "Accept": "application/json"
    },
    body: JSON.stringify(data)
  });
  if (!r.ok) throw new Error(await r.text());
  return r.json();
}
async function apiGet(url) {
  const r = await fetch(url, {
    headers: {
      "Accept": "application/json"
    }
  });
  if (!r.ok) throw new Error(await r.text());
  return r.json();
}

/* ── LOBI ONLINE (realtime via Pusher, sampai 3 lawan sekaligus) ────────── */
function LobiOnline({
  lawanList,
  game,
  level,
  sessionCode,
  isHost = true,
  hostName,
  onBack,
  onMulai,
  onDeclined
}) {
  const [fase, setFase] = useState(sessionCode ? "tunggu" : "kirim");
  const [kode, setKode] = useState(sessionCode || null);
  const [players, setPlayers] = useState([]); // dari GET /game/session/{code}: [{id,nama,status,...}]
  const [memulai, setMemulai] = useState(false);
  const pusherRef = useRef(null);
  const channelRef = useRef(null);
  const pollRef = useRef(null);
  const namaSaya = (window.__GAME_USER__ || {
    nama: "Kamu"
  }).nama;
  useEffect(() => {
    let cancelled = false;
    const pusher = getPusher();
    async function kirim() {
      try {
        let kodeAktif = sessionCode;
        if (!kodeAktif) {
          const res = await apiPost("/game/challenge", {
            opponent_ids: lawanList.map(l => l.id),
            game_type: game.id,
            level: game.id === "memory" ? level : undefined
          });
          if (cancelled) return;
          kodeAktif = res.session_code;
          setKode(kodeAktif);
          setFase("tunggu");
        }
        if (pusher) {
          const ch = pusher.subscribe("private-game-session." + kodeAktif);
          channelRef.current = ch;
          ch.bind("started", () => {
            if (!cancelled) {
              setFase("diterima");
              setTimeout(() => onMulai(kodeAktif), 1000);
            }
          });
        }
        // Polling status semua peserta — juga fallback kalau event Pusher terlewat.
        pollRef.current = setInterval(async () => {
          if (cancelled) return;
          try {
            const s = await apiGet("/game/session/" + kodeAktif);
            if (cancelled) return;
            setPlayers(s.players || []);
            if (s.status === "active") {
              clearInterval(pollRef.current);
              setFase("diterima");
              setTimeout(() => onMulai(kodeAktif), 800);
            }
          } catch (e) {/* abaikan, coba lagi di polling berikutnya */}
        }, 2000);
      } catch (e) {
        if (!cancelled) setFase("error");
      }
    }
    kirim();
    return () => {
      cancelled = true;
      clearInterval(pollRef.current);
      if (channelRef.current && pusher) pusher.unsubscribe(channelRef.current.name);
    };
  }, []);
  const myId = window.__GAME_USER__?.id;
  const lawanStatus = players.filter(p => p.id !== myId);
  const acceptedCount = lawanStatus.filter(p => p.status === "accepted").length;
  const bisaMulai = acceptedCount >= 1 && !memulai;
  const mulaiSekarang = async () => {
    if (!bisaMulai) return;
    setMemulai(true);
    try {
      await apiPost("/game/start", {
        session_code: kode
      });
    } catch (e) {
      setMemulai(false);
    }
  };
  const teks = {
    kirim: "Mengirim tantangan…",
    tunggu: "Menunggu lawan menerima…",
    diterima: "Sesi dimulai! 🎉",
    error: "Gagal mengirim tantangan."
  };
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "24px 20px",
      minHeight: 400,
      maxWidth: 460,
      margin: "0 auto",
      display: "flex",
      flexDirection: "column"
    }
  }, /*#__PURE__*/React.createElement(TopBar, {
    onBack: onBack,
    title: "Menghubungkan…"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      display: "flex",
      flexDirection: "column",
      alignItems: "center",
      justifyContent: "center",
      gap: 20
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexWrap: "wrap",
      justifyContent: "center",
      gap: 16
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement(Avatar, {
    nama: namaSaya,
    size: 54,
    ring: P.gold
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 6,
      fontWeight: 800,
      fontSize: 12,
      color: P.cream
    }
  }, "Kamu")), isHost ? lawanList.map(l => {
    const st = lawanStatus.find(p => p.id === l.id)?.status || "invited";
    const ring = st === "accepted" ? P.green : st === "declined" ? P.red : game.warna;
    return /*#__PURE__*/React.createElement("div", {
      key: l.id,
      style: {
        textAlign: "center",
        opacity: st === "declined" ? .45 : 1
      }
    }, /*#__PURE__*/React.createElement(Avatar, {
      nama: l.nama,
      size: 54,
      ring: ring
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        marginTop: 6,
        fontWeight: 800,
        fontSize: 12,
        color: P.cream
      }
    }, l.nama), /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 10,
        fontWeight: 700,
        color: ring
      }
    }, st === "accepted" ? "Siap ✓" : st === "declined" ? "Menolak" : "Menunggu…"));
  }) : hostName && /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement(Avatar, {
    nama: hostName,
    size: 54,
    ring: game.warna
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 6,
      fontWeight: 800,
      fontSize: 12,
      color: P.cream
    }
  }, hostName), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 10,
      fontWeight: 700,
      color: game.warna
    }
  }, "Host"))), /*#__PURE__*/React.createElement("div", {
    key: fase,
    className: "gf-pop",
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 17,
      textAlign: "center",
      color: fase === "diterima" ? P.green : fase === "error" ? P.red : P.cream
    }
  }, isHost ? teks[fase] : fase === "diterima" ? teks.diterima : `Menunggu ${hostName || "host"} memulai…`), (fase === "kirim" || fase === "tunggu") && /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 6
    }
  }, [0, 1, 2].map(i => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      width: 8,
      height: 8,
      borderRadius: 99,
      background: P.gold,
      animation: `gf-blink 1.2s ${i * .4}s ease infinite`
    }
  }))), kode && fase === "tunggu" && isHost && /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      color: P.muted,
      fontWeight: 600
    }
  }, "Kode sesi: ", kode), fase === "tunggu" && isHost && /*#__PURE__*/React.createElement("button", {
    onClick: mulaiSekarang,
    disabled: !bisaMulai,
    className: "gf-btn",
    style: {
      marginTop: 8,
      padding: "12px 28px",
      borderRadius: 14,
      border: "none",
      background: bisaMulai ? game.warna : "rgba(255,255,255,0.08)",
      color: bisaMulai ? "#1A1340" : P.muted,
      fontWeight: 800,
      fontSize: 14,
      cursor: bisaMulai ? "pointer" : "default"
    }
  }, memulai ? "Memulai…" : acceptedCount > 0 ? `Mulai Sekarang (${acceptedCount + 1} pemain)` : "Menunggu ada yang siap…")));
}

/* ── NOTIF TANTANGAN MASUK ──────────────────────────────────── */
function NotifTantangan({
  notif,
  onTerima,
  onTolak
}) {
  // Di-render lewat portal ke document.body: kartu game punya
  // overflow:hidden+position:relative di root-nya, yang membuat
  // position:fixed di dalamnya jadi terikat ke container itu (dipotong,
  // tidak melebar penuh ke layar) alih-alih ke viewport HP sungguhan.
  //
  // PENTING: transform:translateX(-50%) untuk centering TIDAK BOLEH ada
  // di elemen yang sama dengan class "gf-pop" — @keyframes gf-pop juga
  // mendefinisikan `transform` (scale), dan animation-fill-mode:both
  // membuat transform hasil akhir animasi (scale(1), tanpa translateX)
  // MENIMPA transform inline di elemen itu. Makanya sebelumnya kartu
  // selalu jatuh di x=200 (separuh kanan keluar layar) alih-alih benar-benar
  // center — translateX(-50%)-nya diam-diam dibatalkan oleh animasi.
  // Solusi: wrapper luar untuk positioning+centering, gf-pop cuma di anak.
  const konten = /*#__PURE__*/React.createElement("div", {
    style: {
      position: "fixed",
      bottom: "calc(var(--nav-h, 90px) + 12px)",
      left: "50%",
      transform: "translateX(-50%)",
      width: "calc(100% - 32px)",
      maxWidth: 440,
      zIndex: 9997
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "gf-pop",
    style: {
      padding: "16px 18px",
      borderRadius: 20,
      background: "#241A57",
      border: `1.5px solid ${P.gold}`,
      boxShadow: "0 8px 32px rgba(0,0,0,0.5)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 800,
      fontSize: 14,
      color: P.gold,
      marginBottom: 4
    }
  }, "🎮 Tantangan Masuk!"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 700,
      fontSize: 14,
      color: P.cream,
      marginBottom: 12
    }
  }, /*#__PURE__*/React.createElement("b", null, notif.host_name), " mengajakmu main ", /*#__PURE__*/React.createElement("b", null, notif.game_type)), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onTerima,
    className: "gf-btn",
    style: {
      flex: 1,
      padding: "10px",
      borderRadius: 12,
      border: "none",
      background: P.green,
      color: "#1A1340",
      fontWeight: 800,
      fontSize: 14,
      cursor: "pointer"
    }
  }, "✓ Terima"), /*#__PURE__*/React.createElement("button", {
    onClick: onTolak,
    className: "gf-btn",
    style: {
      flex: 1,
      padding: "10px",
      borderRadius: 12,
      border: `1px solid ${P.red}`,
      background: "transparent",
      color: P.red,
      fontWeight: 800,
      fontSize: 14,
      cursor: "pointer"
    }
  }, "✗ Tolak"))));
  return ReactDOM.createPortal(konten, document.body);
}

/* ── PAPAN PERINGKAT ─────────────────────────────────────────── */
function PapanPeringkat({
  onBack
}) {
  const [tab, setTab] = useState("semua");
  const tabs = [{
    id: "semua",
    label: "Semua"
  }, {
    id: "kuis",
    label: "⚡ Kuis"
  }, {
    id: "susun",
    label: "📖 Susun"
  }, {
    id: "tebak",
    label: "🔍 Tebak"
  }, {
    id: "memory",
    label: "🃏 Memory"
  }];
  const sorted = [...getLeaderboard()].sort((a, b) => (tab === "semua" ? b.poin : b.detail[tab]) - (tab === "semua" ? a.poin : a.detail[tab]));
  const medals = ["🥇", "🥈", "🥉"];
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "20px 20px 32px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement(TopBar, {
    onBack: onBack,
    title: "Papan Peringkat",
    subtitle: "Minggu ini · Reset tiap Senin"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 6,
      marginTop: 16,
      overflowX: "auto",
      paddingBottom: 4
    }
  }, tabs.map(t => /*#__PURE__*/React.createElement("button", {
    key: t.id,
    onClick: () => setTab(t.id),
    className: "gf-btn",
    style: {
      padding: "7px 14px",
      borderRadius: 99,
      flexShrink: 0,
      border: `1px solid ${tab === t.id ? P.gold : "rgba(255,255,255,0.12)"}`,
      background: tab === t.id ? `${P.gold}22` : "rgba(255,255,255,0.04)",
      color: tab === t.id ? P.gold : P.muted,
      fontWeight: 700,
      fontSize: 13
    }
  }, t.label))), sorted.length === 0 ? /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center",
      padding: "40px 20px",
      color: P.muted,
      fontSize: 14
    }
  }, "Belum ada yang bermain minggu ini") : /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 9,
      marginTop: 16
    }
  }, sorted.map((m, i) => {
    const poin = tab === "semua" ? m.poin : m.detail[tab];
    return /*#__PURE__*/React.createElement("div", {
      key: i,
      className: "gf-rise",
      style: {
        display: "flex",
        alignItems: "center",
        gap: 12,
        padding: "13px 16px",
        borderRadius: 16,
        border: `1px solid ${i < 3 ? "rgba(245,196,81,0.3)" : "rgba(255,255,255,0.08)"}`,
        background: i < 3 ? `${P.gold}0a` : "rgba(255,255,255,0.03)",
        animationDelay: `${i * .04}s`
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        width: 26,
        textAlign: "center",
        fontSize: 20,
        fontWeight: 800,
        flexShrink: 0
      }
    }, medals[i] || /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: 14,
        color: P.muted,
        fontWeight: 800
      }
    }, "#", i + 1)), /*#__PURE__*/React.createElement(Avatar, {
      nama: m.nama,
      size: 38
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        fontWeight: 800,
        fontSize: 15,
        color: P.cream
      }
    }, m.nama)), /*#__PURE__*/React.createElement("div", {
      style: {
        fontFamily: "'Bricolage Grotesque',sans-serif",
        fontWeight: 800,
        fontSize: 20,
        color: i === 0 ? P.gold : P.cream
      }
    }, poin.toLocaleString()));
  })));
}

/* ════════════════════════════════════════════════════════════
   GAME 1: KUIS ADU CEPAT
════════════════════════════════════════════════════════════ */
function optState(i, pilih, benar) {
  if (pilih === null) return "idle";
  if (i === benar) return "benar";
  if (i === pilih) return "salah";
  return "redup";
}
function KuisSolo({
  onExit
}) {
  const [soal] = useState(() => siapkanSoal(10));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(0);
  const [streak, setStreak] = useState(0);
  const [best, setBest] = useState(0);
  const [benarTotal, setBenarTotal] = useState(0);
  const [pilih, setPilih] = useState(null);
  const [waktu, setWaktu] = useState(15);
  const [selesai, setSelesai] = useState(false);
  const [poin, setPoin] = useState(0);
  const timerRef = useRef();
  const s = soal[idx];
  const lanjut = useCallback(() => {
    if (idx + 1 >= soal.length) setSelesai(true);else {
      setIdx(i => i + 1);
      setPilih(null);
      setWaktu(15);
    }
  }, [idx, soal.length]);
  const jawab = useCallback(i => {
    if (pilih !== null) return;
    clearInterval(timerRef.current);
    setPilih(i);
    if (i === s.benar) {
      const p = 100 + Math.round(waktu / 15 * 100) + streak * 20;
      setPoin(p);
      setSkor(sc => sc + p);
      setBenarTotal(b => b + 1);
      setStreak(st => {
        const n = st + 1;
        setBest(bs => Math.max(bs, n));
        return n;
      });
    } else {
      setPoin(0);
      setStreak(0);
    }
    setTimeout(lanjut, 1400);
  }, [pilih, s, waktu, streak, lanjut]);
  useEffect(() => {
    if (selesai || pilih !== null) return;
    timerRef.current = setInterval(() => {
      setWaktu(w => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          setPilih(-1);
          setStreak(0);
          setPoin(0);
          setTimeout(lanjut, 1400);
          return 0;
        }
        return +(w - .1).toFixed(1);
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, pilih, selesai, lanjut]);
  if (selesai) return /*#__PURE__*/React.createElement(Hasil, {
    judul: "Selesai! 🎉",
    skor: skor,
    accent: P.gold,
    onExit: onExit,
    baris: [{
      label: "Jawaban benar",
      val: `${benarTotal}/10`
    }, {
      label: "Streak terbaik",
      val: `${best} 🔥`
    }, {
      label: "Akurasi",
      val: `${Math.round(benarTotal / 10 * 100)}%`
    }]
  });
  const ratio = waktu / 15;
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 28px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement(Pill, {
    color: P.gold,
    label: `${skor} pts`
  }), /*#__PURE__*/React.createElement(Pill, {
    color: P.red,
    label: `${streak}🔥`,
    dim: streak === 0
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 16
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      fontSize: 12.5,
      fontWeight: 700,
      color: P.muted,
      marginBottom: 7
    }
  }, /*#__PURE__*/React.createElement("span", null, "Soal ", idx + 1, "/10"), /*#__PURE__*/React.createElement("span", {
    style: {
      color: ratio < .3 ? P.red : P.gold
    }
  }, Math.ceil(waktu), " dtk")), /*#__PURE__*/React.createElement("div", {
    style: {
      height: 6,
      background: "rgba(255,255,255,0.1)",
      borderRadius: 99,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      height: "100%",
      width: `${ratio * 100}%`,
      background: ratio < .3 ? P.red : `linear-gradient(90deg,${P.gold},${P.goldSoft})`,
      transition: "width .1s linear"
    }
  }))), /*#__PURE__*/React.createElement("div", {
    key: idx,
    className: "gf-pop",
    style: {
      marginTop: 22
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 700,
      color: P.gold,
      letterSpacing: 1,
      textTransform: "uppercase"
    }
  }, "Pertanyaan"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 22,
      lineHeight: 1.25,
      margin: "8px 0 0",
      color: P.cream
    }
  }, s.q)), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 10,
      marginTop: 20
    }
  }, s.opsi.map((op, i) => /*#__PURE__*/React.createElement(OptBtn, {
    key: i,
    text: op,
    idx: i,
    state: optState(i, pilih, s.benar),
    onClick: () => jawab(i),
    disabled: pilih !== null,
    delay: i * .05
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      minHeight: 30,
      marginTop: 12,
      textAlign: "center"
    }
  }, pilih !== null && /*#__PURE__*/React.createElement("div", {
    className: "gf-pop",
    style: {
      fontWeight: 800,
      fontSize: 16,
      color: poin > 0 ? P.green : P.red,
      fontFamily: "'Bricolage Grotesque',sans-serif"
    }
  }, poin > 0 ? `+${poin} poin!` : pilih === -1 ? "Waktu habis ⏱" : "Belum tepat")));
}
function KuisTatap({
  lawan,
  onExit
}) {
  const [soal] = useState(() => siapkanSoal(7));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({
    p1: 0,
    p2: 0
  });
  const [lock, setLock] = useState({
    p1: false,
    p2: false
  });
  const [pilih, setPilih] = useState({
    p1: null,
    p2: null
  });
  const [winner, setWinner] = useState(null);
  const [waktu, setWaktu] = useState(12);
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const s = soal[idx];
  const lanjut = useCallback(() => {
    if (idx + 1 >= soal.length) setSelesai(true);else {
      setIdx(i => i + 1);
      setLock({
        p1: false,
        p2: false
      });
      setPilih({
        p1: null,
        p2: null
      });
      setWinner(null);
      setWaktu(12);
    }
  }, [idx, soal.length]);
  const jawab = useCallback((pem, i) => {
    if (winner || lock[pem] || pilih[pem] !== null) return;
    setPilih(p => ({
      ...p,
      [pem]: i
    }));
    if (i === s.benar) {
      clearInterval(timerRef.current);
      const p = 100 + Math.round(waktu / 12 * 50);
      setSkor(sc => ({
        ...sc,
        [pem]: sc[pem] + p
      }));
      setWinner(pem);
      setTimeout(lanjut, 1500);
    } else setLock(l => ({
      ...l,
      [pem]: true
    }));
  }, [winner, lock, pilih, s, waktu, lanjut]);
  useEffect(() => {
    if (selesai || winner) return;
    timerRef.current = setInterval(() => {
      setWaktu(w => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          setWinner("seri");
          setTimeout(lanjut, 1500);
          return 0;
        }
        return +(w - .1).toFixed(1);
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, winner, selesai, lanjut]);
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! 🏆" : `${lawan.nama} Menang! 🏆`;
    return /*#__PURE__*/React.createElement(Hasil, {
      judul: w,
      skor: null,
      accent: skor.p1 >= skor.p2 ? P.p1 : P.p2,
      onExit: onExit,
      custom: /*#__PURE__*/React.createElement("div", {
        style: {
          display: "flex",
          gap: 12,
          justifyContent: "center",
          marginTop: 8
        }
      }, /*#__PURE__*/React.createElement(ScorePill, {
        name: "Kamu",
        val: skor.p1,
        color: P.p1,
        win: skor.p1 >= skor.p2
      }), /*#__PURE__*/React.createElement(ScorePill, {
        name: lawan.nama,
        val: skor.p2,
        color: P.p2,
        win: skor.p2 >= skor.p1
      }))
    });
  }
  const ratio = waktu / 12;
  const Panel = ({
    pem,
    nama,
    color,
    flip
  }) => {
    const w = winner === pem;
    return /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1,
        padding: "14px 16px",
        display: "flex",
        flexDirection: "column",
        transform: flip ? "rotate(180deg)" : "none",
        background: w ? `${color}14` : "transparent",
        transition: "background .3s"
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center",
        marginBottom: 10
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        alignItems: "center",
        gap: 8
      }
    }, /*#__PURE__*/React.createElement(Avatar, {
      nama: nama,
      size: 26
    }), /*#__PURE__*/React.createElement("span", {
      style: {
        fontWeight: 800,
        fontSize: 13,
        color
      }
    }, nama)), /*#__PURE__*/React.createElement("span", {
      style: {
        fontFamily: "'Bricolage Grotesque',sans-serif",
        fontWeight: 800,
        fontSize: 18,
        color
      }
    }, skor[pem])), /*#__PURE__*/React.createElement("h3", {
      style: {
        fontFamily: "'Bricolage Grotesque',sans-serif",
        fontWeight: 800,
        fontSize: 16,
        lineHeight: 1.25,
        margin: "0 0 10px",
        color: P.cream
      }
    }, s.q), /*#__PURE__*/React.createElement("div", {
      style: {
        display: "grid",
        gridTemplateColumns: "1fr 1fr",
        gap: 7,
        flex: 1
      }
    }, s.opsi.map((op, i) => {
      let st = "idle";
      if (winner) {
        st = i === s.benar ? "benar" : i === pilih[pem] ? "salah" : "redup";
      } else if (pilih[pem] === i) st = "salah";else if (lock[pem]) st = "redup";
      return /*#__PURE__*/React.createElement("button", {
        key: i,
        onClick: () => jawab(pem, i),
        disabled: !!winner || lock[pem] || pilih[pem] !== null,
        className: "gf-btn",
        style: {
          padding: "11px",
          borderRadius: 12,
          border: `1.5px solid ${st === "benar" ? P.green : st === "salah" ? P.red : "rgba(255,255,255,0.12)"}`,
          background: st === "benar" ? `${P.green}22` : st === "salah" ? `${P.red}1f` : "rgba(255,255,255,0.05)",
          color: P.cream,
          cursor: "pointer",
          fontSize: 13.5,
          fontWeight: 700,
          lineHeight: 1.2,
          minHeight: 48
        }
      }, op);
    })), w && /*#__PURE__*/React.createElement("div", {
      className: "gf-pop",
      style: {
        textAlign: "center",
        marginTop: 8,
        fontWeight: 800,
        color,
        fontSize: 14
      }
    }, "Tercepat! ⚡"), winner && !w && winner !== "seri" && /*#__PURE__*/React.createElement("div", {
      style: {
        textAlign: "center",
        marginTop: 8,
        color: P.muted,
        fontSize: 13,
        fontWeight: 700
      }
    }, "Keduluan…"), winner === "seri" && /*#__PURE__*/React.createElement("div", {
      style: {
        textAlign: "center",
        marginTop: 8,
        color: P.muted,
        fontSize: 13,
        fontWeight: 700
      }
    }, "Waktu habis"), lock[pem] && !winner && /*#__PURE__*/React.createElement("div", {
      className: "gf-shake",
      style: {
        textAlign: "center",
        marginTop: 8,
        color: P.red,
        fontSize: 13,
        fontWeight: 700
      }
    }, "Terkunci ronde ini ✗"));
  };
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      minHeight: 560
    }
  }, /*#__PURE__*/React.createElement(Panel, {
    pem: "p2",
    nama: lawan.nama,
    color: P.p2,
    flip: true
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      gap: 14,
      padding: "8px 16px",
      background: "rgba(0,0,0,0.3)",
      position: "relative"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: {
      ...gBtn,
      padding: "5px 10px",
      position: "absolute",
      left: 10
    }
  }, "←"), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 11,
      fontWeight: 800,
      color: P.muted,
      letterSpacing: 1
    }
  }, "RONDE ", idx + 1, "/", soal.length), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "grid",
      placeItems: "center"
    }
  }, /*#__PURE__*/React.createElement(TimerRing, {
    ratio: ratio,
    danger: ratio < .3,
    size: 50
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      fontWeight: 800,
      fontSize: 15,
      fontFamily: "'Bricolage Grotesque',sans-serif",
      color: ratio < .3 ? P.red : P.cream
    }
  }, Math.ceil(waktu))), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 11,
      fontWeight: 800,
      color: P.muted,
      letterSpacing: 1
    }
  }, "ADU CEPAT")), /*#__PURE__*/React.createElement(Panel, {
    pem: "p1",
    nama: "Kamu",
    color: P.p1
  }));
}

/* ── HOOK: SINKRONISASI GAME ONLINE VIA PUSHER ──────────────── */
function useOnlineGame(sessionCode, onMove, onEnded) {
  const pusherRef = useRef(null);
  const chRef = useRef(null);
  // onMove/onEnded dibuat ulang tiap render (menutup state ronde saat ini).
  // Simpan versi TERBARU di ref supaya listener Pusher (dipasang sekali saat
  // mount) selalu memanggil closure terkini — bukan closure basi dari ronde
  // pertama, yang sebelumnya bikin game berhenti merespons di tengah main.
  const onMoveRef = useRef(onMove);
  const onEndedRef = useRef(onEnded);
  onMoveRef.current = onMove;
  onEndedRef.current = onEnded;

  // Pemain yang keluar mid-game (tombol Keluar / tutup tab) — ditangani di
  // sini (bukan diulang di tiap komponen game) supaya keempat game online
  // dapat perilaku yang sama: tampilkan siapa yang keluar + dialog pilihan
  // "lanjutkan tanpa dia" / "akhiri sesi" untuk pemain yang masih tersisa.
  const [pemainKeluar, setPemainKeluar] = useState(null); // {id,nama} — dialog aktif kalau ada isinya
  const [pemainDitandaiKeluar, setPemainDitandaiKeluar] = useState(null); // id — tetap tersimpan setelah dialog ditutup, untuk indikator "(keluar)" di papan skor
  const [sesiDiakhiriKarenaKeluar, setSesiDiakhiriKarenaKeluar] = useState(false);

  // Emoji reaction — murni ephemeral (tidak disimpan ke database), numpang
  // lewat channel "move" yang sama seperti player_left dkk. reaksiMasuk
  // dipakai ReactionOverlay untuk animasikan lalu otomatis hilang sendiri.
  const [reaksiMasuk, setReaksiMasuk] = useState(null); // {id (unik per kemunculan), userId, emoji}
  const reaksiIdRef = useRef(0);
  useEffect(() => {
    if (!sessionCode) return;
    const pusher = getPusher();
    if (!pusher) return;
    const ch = pusher.subscribe("private-game-session." + sessionCode);
    chRef.current = ch;
    ch.bind("move", d => {
      const p = d.payload || {};
      if (p.type === "player_left") {
        setPemainKeluar({
          id: p.user_id,
          nama: p.user_name || "Pemain"
        });
        setPemainDitandaiKeluar(p.user_id);
        return; // jangan diteruskan ke onMove — ini bukan gerakan permainan
      }
      if (p.type === "session_continued") {
        setPemainKeluar(null); // dialog tertutup, lanjut seperti biasa
        return;
      }
      if (p.type === "session_ended_by_leave") {
        setSesiDiakhiriKarenaKeluar(true);
        return;
      }
      if (p.type === "reaction") {
        reaksiIdRef.current += 1;
        setReaksiMasuk({
          id: reaksiIdRef.current,
          userId: p.user_id,
          emoji: p.emoji
        });
        return; // ephemeral — jangan diteruskan ke onMove
      }
      onMoveRef.current && onMoveRef.current(d);
    });
    ch.bind("ended", d => onEndedRef.current && onEndedRef.current(d));
    return () => {
      pusher.unsubscribe("private-game-session." + sessionCode);
    };
  }, [sessionCode]);
  const sendMove = useCallback(async payload => {
    if (!sessionCode) return;
    try {
      await apiPost("/game/move", {
        session_code: sessionCode,
        payload
      });
    } catch (e) {
      console.error("[Game] Gagal kirim move:", e);
    }
  }, [sessionCode]);

  // PENTING: kalau POST ini gagal diam-diam (jaringan sempat putus, CSRF
  // token kedaluwarsa di sesi yang lama terbuka, dll) dan tidak pernah
  // dicoba ulang, baris participant pemain ini permanen finished=false —
  // sesi tidak akan PERNAH pindah ke status 'finished' (lihat
  // GameSessionController::move()), dan pemain itu tampak "Sedang main"
  // selamanya di daftar lawan walau layar Selesai sudah tampil di
  // perangkatnya sendiri (layar itu murni state lokal, tidak bergantung
  // pada POST ini berhasil). Retry beberapa kali dengan jeda singkat
  // sebelum benar-benar menyerah.
  const sendFinished = useCallback(async score => {
    if (!sessionCode) return;
    for (let percobaan = 1; percobaan <= 3; percobaan++) {
      try {
        await apiPost("/game/move", {
          session_code: sessionCode,
          payload: {
            finished: true,
            score
          }
        });
        return;
      } catch (e) {
        console.error(`[Game] Gagal kirim finished (percobaan ${percobaan}/3):`, e);
        if (percobaan < 3) await new Promise(r => setTimeout(r, 1000 * percobaan));
      }
    }
  }, [sessionCode]);
  const keluarDariSesi = useCallback(() => {
    if (!sessionCode) return;
    // sendBeacon supaya sinyal tetap terkirim walau tab langsung ditutup
    // (fetch biasa bisa dibatalkan browser saat halaman unload).
    const token = document.querySelector('meta[name="csrf-token"]')?.content || "";
    const data = new Blob([JSON.stringify({
      session_code: sessionCode
    })], {
      type: "application/json"
    });
    if (navigator.sendBeacon) {
      navigator.sendBeacon("/game/leave?_token=" + encodeURIComponent(token), data);
    } else {
      apiPost("/game/leave", {
        session_code: sessionCode
      }).catch(() => {});
    }
  }, [sessionCode]);

  // "beforeunload" SAJA tidak cukup — banyak browser mobile (Chrome/Safari
  // Android/iOS) TIDAK memicu beforeunload saat user menutup app lewat
  // tombol home/app-switcher/kunci layar, yang justru cara paling umum
  // "keluar" di HP. Akibatnya sinyal /game/leave tidak pernah terkirim,
  // dan pemain lain tidak pernah lihat notifikasi "pemain keluar" sama
  // sekali — bukan salah tampil, tapi memang tidak pernah muncul.
  // "pagehide" jauh lebih reliabel lintas platform untuk kasus ini (juga
  // menangkap kasus bfcache di mobile yang tidak memicu beforeunload).
  // TIDAK pakai "visibilitychange" di sini — itu juga terpicu saat user
  // cuma sebentar pindah app/kunci layar lalu balik lagi, yang seharusnya
  // TIDAK mengeluarkan pemain dari match yang masih berlangsung.
  useEffect(() => {
    const handler = () => keluarDariSesi();
    window.addEventListener("beforeunload", handler);
    window.addEventListener("pagehide", handler);
    return () => {
      window.removeEventListener("beforeunload", handler);
      window.removeEventListener("pagehide", handler);
    };
  }, [keluarDariSesi]);
  const putuskanKelanjutan = useCallback(async action => {
    if (!sessionCode) return;
    try {
      await apiPost("/game/resolve-leave", {
        session_code: sessionCode,
        action
      });
    } catch (e) {
      console.error("[Game] Gagal kirim keputusan:", e);
    }
    if (action === "continue") setPemainKeluar(null);
  }, [sessionCode]);

  // Cooldown 2 detik per pemain supaya tidak spam — dicek di sisi PENGIRIM
  // saja (client-side), cukup untuk mencegah tap beruntun tak sengaja;
  // ini fitur sosial ringan, bukan sesuatu yang perlu ditegakkan server.
  // TIDAK optimistic-update: broadcast GameMove selalu dikirim balik ke
  // pengirimnya sendiri juga (lihat pola answer_correct di KuisOnline dkk),
  // jadi cukup kirim ke server dan biarkan semua pemain — termasuk diri
  // sendiri — menerima & menampilkannya lewat listener "move" yang sama.
  const kirimReaksiCooldownRef = useRef(0);
  const kirimReaksi = useCallback(emoji => {
    if (!sessionCode) return;
    const sekarang = Date.now();
    if (sekarang - kirimReaksiCooldownRef.current < 2000) return;
    kirimReaksiCooldownRef.current = sekarang;
    apiPost("/game/move", {
      session_code: sessionCode,
      payload: {
        type: "reaction",
        emoji
      }
    }).catch(() => {});
  }, [sessionCode]);
  return {
    sendMove,
    sendFinished,
    keluarDariSesi,
    pemainKeluar,
    pemainDitandaiKeluar,
    sesiDiakhiriKarenaKeluar,
    putuskanKelanjutan,
    reaksiMasuk,
    kirimReaksi
  };
}

/* ── DIALOG: SALAH SATU PEMAIN KELUAR DI TENGAH GAME ─────────── */
function DialogPemainKeluar({
  nama,
  onLanjut,
  onAkhiri
}) {
  const konten = /*#__PURE__*/React.createElement("div", {
    style: {
      position: "fixed",
      inset: 0,
      zIndex: 9999,
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      background: "rgba(0,0,0,0.6)",
      padding: 20
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "gf-pop",
    style: {
      width: "100%",
      maxWidth: 380,
      padding: "22px 20px",
      borderRadius: 20,
      background: "#241A57",
      border: `1.5px solid ${P.gold}`,
      boxShadow: "0 8px 32px rgba(0,0,0,0.5)",
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 32,
      marginBottom: 8
    }
  }, "🚪"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 800,
      fontSize: 16,
      color: P.cream,
      marginBottom: 6
    }
  }, nama, " keluar dari game"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 13,
      color: P.muted,
      fontWeight: 600,
      marginBottom: 18
    }
  }, "Lanjutkan permainan tanpa ", nama, ", atau akhiri sesi ini untuk semua?"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onLanjut,
    className: "gf-btn",
    style: {
      padding: "12px",
      borderRadius: 12,
      border: "none",
      background: P.green,
      color: "#1A1340",
      fontWeight: 800,
      fontSize: 14,
      cursor: "pointer"
    }
  }, "Lanjutkan Tanpa ", nama), /*#__PURE__*/React.createElement("button", {
    onClick: onAkhiri,
    className: "gf-btn",
    style: {
      padding: "12px",
      borderRadius: 12,
      border: `1px solid ${P.red}`,
      background: "transparent",
      color: P.red,
      fontWeight: 800,
      fontSize: 14,
      cursor: "pointer"
    }
  }, "Akhiri Sesi"))));
  return ReactDOM.createPortal(konten, document.body);
}

/* ── EMOJI REACTION — baris tetap 5 emoji + overlay animasi terapung ── */
const REACTION_EMOJI = ["😂", "😮", "🔥", "👏", "😢"];
function EmojiReactionBar({
  onKirim
}) {
  const konten = /*#__PURE__*/React.createElement("div", {
    style: {
      position: "fixed",
      left: 0,
      right: 0,
      bottom: 0,
      zIndex: 9997,
      display: "flex",
      justifyContent: "center",
      padding: "10px 14px calc(10px + env(safe-area-inset-bottom))",
      pointerEvents: "none"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 8,
      padding: "8px 10px",
      borderRadius: 99,
      background: "rgba(26,19,64,0.85)",
      backdropFilter: "blur(8px)",
      border: "1px solid rgba(255,255,255,0.1)",
      boxShadow: "0 6px 24px rgba(0,0,0,0.35)",
      pointerEvents: "auto"
    }
  }, REACTION_EMOJI.map(e => /*#__PURE__*/React.createElement("button", {
    key: e,
    onClick: () => onKirim(e),
    style: {
      width: 38,
      height: 38,
      borderRadius: "50%",
      border: "none",
      background: "rgba(255,255,255,0.06)",
      fontSize: 19,
      cursor: "pointer",
      display: "grid",
      placeItems: "center"
    }
  }, e))));
  return ReactDOM.createPortal(konten, document.body);
}

// Berapa salinan emoji yang muncul serentak untuk SATU reaksi yang masuk —
// efek "burst" beberapa emoji bertebaran, bukan cuma 1 emoji tunggal per tap.
const REACTION_BURST_COUNT = 6;
function ReactionOverlay({
  reaksi,
  players
}) {
  const [tampil, setTampil] = useState([]); // salinan emoji yang lagi mengambang (burst)
  const [label, setLabel] = useState(null); // {id,userId,left} — nama pengirim, satu per burst
  const burstIdRef = useRef(0);
  useEffect(() => {
    if (!reaksi) return;
    const leftBurst = 10 + Math.random() * 70;
    const items = Array.from({
      length: REACTION_BURST_COUNT
    }, (_, i) => {
      burstIdRef.current += 1;
      return {
        id: burstIdRef.current,
        emoji: reaksi.emoji,
        left: Math.min(85, Math.max(5, leftBurst + (Math.random() * 30 - 15))),
        delay: i * 80 + Math.random() * 60,
        // ms — biar tidak muncul barengan persis, terasa "bertebaran"
        size: 28 + Math.random() * 14
      };
    });
    setTampil(prev => [...prev, ...items]);
    setLabel({
      id: reaksi.id,
      userId: reaksi.userId,
      left: leftBurst
    });
    const timer = setTimeout(() => {
      const ids = new Set(items.map(x => x.id));
      setTampil(prev => prev.filter(x => !ids.has(x.id)));
      setLabel(prev => prev?.id === reaksi.id ? null : prev);
    }, 2000);
    return () => clearTimeout(timer);
  }, [reaksi]);
  if (tampil.length === 0) return null;
  const namaFor = userId => players.find(p => p.id === userId)?.nama || "";
  const konten = /*#__PURE__*/React.createElement("div", {
    style: {
      position: "fixed",
      inset: 0,
      zIndex: 9996,
      pointerEvents: "none",
      overflow: "hidden"
    }
  }, tampil.map(t => /*#__PURE__*/React.createElement("div", {
    key: t.id,
    className: "gf-float-emoji",
    style: {
      position: "absolute",
      left: `${t.left}%`,
      bottom: 70,
      textAlign: "center",
      animationDelay: `${t.delay}ms`
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: t.size,
      lineHeight: 1
    }
  }, t.emoji))), label && /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: `${label.left}%`,
      bottom: 70,
      textAlign: "center",
      transform: "translateX(-50%)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 10,
      fontWeight: 700,
      color: P.cream,
      opacity: .85,
      whiteSpace: "nowrap"
    }
  }, namaFor(label.userId))));
  return ReactDOM.createPortal(konten, document.body);
}

/* ── PAPAN SKOR N-PEMAIN (2-4 orang) ─────────────────────────── */
// players: [{id, nama}] SEMUA peserta termasuk diri sendiri.
// scores: {[id]: number}. tengah: elemen opsional (mis. timer) di antara kartu skor.
const PEMAIN_WARNA = [P.gold, P.p2, P.purple, P.orange];
function warnaPemain(idx) {
  return PEMAIN_WARNA[idx % PEMAIN_WARNA.length];
}
function PapanSkorN({
  players,
  scores,
  myId,
  tengah,
  pemainKeluarId
}) {
  const urut = [...players].sort((a, b) => a.id === myId ? -1 : b.id === myId ? 1 : 0);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "flex-start",
      justifyContent: "space-between",
      marginTop: 14,
      gap: 8,
      flexWrap: "wrap"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 8,
      flexWrap: "wrap",
      flex: 1
    }
  }, urut.map((p, i) => {
    const warna = p.id === myId ? P.gold : warnaPemain(i);
    const keluar = p.id === pemainKeluarId;
    return /*#__PURE__*/React.createElement("div", {
      key: p.id,
      style: {
        display: "flex",
        alignItems: "center",
        gap: 6,
        padding: "5px 9px 5px 5px",
        borderRadius: 99,
        background: p.id === myId ? `${P.gold}14` : "rgba(255,255,255,0.04)",
        opacity: keluar ? .4 : 1
      }
    }, /*#__PURE__*/React.createElement(Avatar, {
      nama: p.nama,
      size: 26,
      ring: warna
    }), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
      style: {
        fontWeight: 700,
        fontSize: 10.5,
        color: warna,
        maxWidth: 70,
        overflow: "hidden",
        textOverflow: "ellipsis",
        whiteSpace: "nowrap"
      }
    }, p.id === myId ? "Kamu" : p.nama, keluar ? " (keluar)" : ""), /*#__PURE__*/React.createElement("div", {
      style: {
        fontFamily: "'Bricolage Grotesque',sans-serif",
        fontWeight: 800,
        fontSize: 15,
        color: P.cream,
        lineHeight: 1
      }
    }, scores[p.id] ?? 0)));
  })), tengah);
}

/* ── HASIL AKHIR N-PEMAIN (leaderboard ronde, bukan cuma kamu vs 1 lawan) ── */
function HasilN({
  players,
  scores,
  myId,
  accent,
  onExit
}) {
  const urut = [...players].sort((a, b) => (scores[b.id] ?? 0) - (scores[a.id] ?? 0));
  const topScore = scores[urut[0]?.id] ?? 0;
  const skorSaya = scores[myId] ?? 0;
  const menang = skorSaya === topScore;
  const medals = ["🥇", "🥈", "🥉", "4️⃣"];
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "40px 24px",
      maxWidth: 460,
      margin: "0 auto",
      textAlign: "center",
      display: "flex",
      flexDirection: "column",
      minHeight: 480,
      justifyContent: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "gf-pop"
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 72,
      height: 72,
      margin: "0 auto",
      borderRadius: 24,
      background: `linear-gradient(135deg,${accent},${accent}99)`,
      display: "grid",
      placeItems: "center",
      boxShadow: `0 12px 40px ${accent}55`
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 32
    }
  }, menang ? "🏆" : "⭐")), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 24,
      margin: "18px 0 4px",
      color: P.cream
    }
  }, menang ? "Kamu Menang! 🏆" : "Permainan Selesai")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 9,
      marginTop: 20
    }
  }, urut.map((p, i) => /*#__PURE__*/React.createElement("div", {
    key: p.id,
    className: "gf-rise",
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      padding: "12px 16px",
      borderRadius: 14,
      background: p.id === myId ? `${accent}14` : "rgba(255,255,255,0.04)",
      border: `1px solid ${p.id === myId ? accent : "rgba(255,255,255,0.08)"}`,
      animationDelay: `${i * .05}s`
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 18,
      width: 24,
      flexShrink: 0
    }
  }, medals[i] || `#${i + 1}`), /*#__PURE__*/React.createElement(Avatar, {
    nama: p.nama,
    size: 32
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      flex: 1,
      textAlign: "left",
      fontWeight: 800,
      fontSize: 14,
      color: P.cream
    }
  }, p.id === myId ? "Kamu" : p.nama), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 18,
      color: p.id === myId ? accent : P.cream
    }
  }, scores[p.id] ?? 0)))), /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: {
      marginTop: 28,
      padding: 16,
      borderRadius: 16,
      border: "none",
      background: `linear-gradient(135deg,${accent},${accent}cc)`,
      color: "#1A1340",
      fontWeight: 800,
      fontSize: 16,
      cursor: "pointer",
      fontFamily: "'Bricolage Grotesque',sans-serif"
    }
  }, "Kembali ke Menu"));
}
function KuisOnline({
  players,
  sessionCode,
  hindariKeys,
  hostId,
  onExit
}) {
  const myId = window.__GAME_USER__?.id;
  const [soal] = useState(() => siapkanSoalSeed(7, sessionCode + ":kuis", hindariKeys));
  useEffect(() => {
    if (myId === hostId) apiPost("/game/record-questions", {
      session_code: sessionCode,
      keys: keyDariSoal(soal)
    }).catch(() => {});
  }, []);
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(() => Object.fromEntries(players.map(p => [p.id, 0])));
  const [pilih, setPilih] = useState(null);
  const [youLock, setYouLock] = useState(false);
  const [pemenangRonde, setPemenangRonde] = useState(null); // {id,nama} atau "seri" atau null
  const [waktu, setWaktu] = useState(12);
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const resolvedRef = useRef(false);
  const waktuRef = useRef(12);
  const youLockRef = useRef(false);
  const s = soal[idx];
  const lanjut = useCallback(() => {
    if (idx + 1 >= soal.length) setSelesai(true);else {
      setIdx(i => i + 1);
      setPilih(null);
      setYouLock(false);
      setPemenangRonde(null);
      setWaktu(12);
    }
  }, [idx, soal.length]);
  const {
    sendMove,
    sendFinished,
    keluarDariSesi,
    pemainKeluar,
    pemainDitandaiKeluar,
    sesiDiakhiriKarenaKeluar,
    putuskanKelanjutan,
    reaksiMasuk,
    kirimReaksi
  } = useOnlineGame(sessionCode, d => {
    const p = d.payload || {};
    if (p.type === "answer_correct" && !resolvedRef.current) {
      resolvedRef.current = true;
      clearInterval(timerRef.current);
      const pemain = players.find(pl => pl.id === d.user_id);
      setPemenangRonde(pemain || {
        id: d.user_id,
        nama: "?"
      });
      if (d.scores) setSkor(d.scores);
      setTimeout(lanjut, 1700);
    }
  }, d => {
    setSkor(Object.fromEntries(d.players.map(p => [p.user_id, p.score])));
    setSelesai(true);
  });
  useEffect(() => {
    if (sesiDiakhiriKarenaKeluar) onExit();
  }, [sesiDiakhiriKarenaKeluar]);
  const jawab = useCallback(i => {
    if (resolvedRef.current || pilih !== null || youLockRef.current) return;
    setPilih(i);
    if (i === s.benar) {
      const poin = 100 + Math.round(waktuRef.current / 12 * 50);
      resolvedRef.current = true;
      clearInterval(timerRef.current);
      setPemenangRonde({
        id: myId,
        nama: "Kamu"
      });
      const skorBaru = (skor[myId] || 0) + poin;
      setSkor(sk => ({
        ...sk,
        [myId]: skorBaru
      }));
      sendMove({
        type: "answer_correct",
        ronde: idx,
        score: skorBaru
      });
      setTimeout(lanjut, 1700);
    } else {
      youLockRef.current = true;
      setYouLock(true);
      sendMove({
        type: "answer_wrong",
        ronde: idx
      });
    }
  }, [pilih, s, idx, skor, myId, sendMove, lanjut]);
  useEffect(() => {
    resolvedRef.current = false;
    youLockRef.current = false;
    waktuRef.current = 12;
  }, [idx]);
  useEffect(() => {
    if (selesai) return;
    timerRef.current = setInterval(() => {
      setWaktu(w => {
        const n = w <= 0.1 ? 0 : +(w - .1).toFixed(1);
        waktuRef.current = n;
        if (n === 0 && !resolvedRef.current) {
          resolvedRef.current = true;
          setPemenangRonde("seri");
          setTimeout(lanjut, 1700);
        }
        return n;
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, selesai, lanjut]);
  useEffect(() => {
    if (selesai) sendFinished(skor[myId] || 0);
  }, [selesai]);
  if (selesai) return /*#__PURE__*/React.createElement(HasilN, {
    players: players,
    scores: skor,
    myId: myId,
    accent: P.gold,
    onExit: onExit
  });
  const ratio = waktu / 12;
  const statusTeks = pemenangRonde === "seri" ? "Waktu habis — ronde seri" : pemenangRonde ? pemenangRonde.id === myId ? "Kamu tercepat! ⚡" : `${pemenangRonde.nama} lebih cepat` : youLock ? "Jawabanmu salah — tunggu pemain lain…" : "Jawab secepat mungkin!";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 26px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, pemainKeluar && /*#__PURE__*/React.createElement(DialogPemainKeluar, {
    nama: pemainKeluar.nama,
    onLanjut: () => putuskanKelanjutan("continue"),
    onAkhiri: () => putuskanKelanjutan("end")
  }), /*#__PURE__*/React.createElement(EmojiReactionBar, {
    onKirim: kirimReaksi
  }), /*#__PURE__*/React.createElement(ReactionOverlay, {
    reaksi: reaksiMasuk,
    players: players
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => {
      keluarDariSesi();
      onExit();
    },
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 11,
      fontWeight: 800,
      color: P.muted,
      letterSpacing: 1
    }
  }, "RONDE ", idx + 1, "/", soal.length)), /*#__PURE__*/React.createElement(PapanSkorN, {
    players: players,
    scores: skor,
    myId: myId,
    pemainKeluarId: pemainDitandaiKeluar,
    tengah: /*#__PURE__*/React.createElement("div", {
      style: {
        position: "relative",
        display: "grid",
        placeItems: "center",
        flexShrink: 0
      }
    }, /*#__PURE__*/React.createElement(TimerRing, {
      ratio: ratio,
      danger: ratio < .3,
      size: 48
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        position: "absolute",
        fontWeight: 800,
        fontSize: 14,
        color: ratio < .3 ? P.red : P.cream
      }
    }, Math.ceil(waktu)))
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 10,
      padding: "9px 14px",
      borderRadius: 12,
      background: "rgba(255,255,255,0.04)",
      border: "1px solid rgba(255,255,255,0.08)",
      fontSize: 13,
      fontWeight: 700,
      color: pemenangRonde && pemenangRonde !== "seri" ? pemenangRonde.id === myId ? P.green : P.p2 : P.muted
    }
  }, statusTeks), /*#__PURE__*/React.createElement("div", {
    key: idx,
    className: "gf-pop",
    style: {
      marginTop: 18
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 700,
      color: P.gold,
      letterSpacing: 1,
      textTransform: "uppercase"
    }
  }, "Pertanyaan"), /*#__PURE__*/React.createElement("h2", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 21,
      lineHeight: 1.25,
      margin: "7px 0 0",
      color: P.cream
    }
  }, s.q)), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 9,
      marginTop: 16
    }
  }, s.opsi.map((op, i) => {
    let st = "idle";
    if (pemenangRonde) {
      st = i === s.benar ? "benar" : i === pilih ? "salah" : "redup";
    } else if (pilih === i) st = "salah";else if (youLock) st = "redup";
    return /*#__PURE__*/React.createElement(OptBtn, {
      key: i,
      text: op,
      idx: i,
      state: st,
      onClick: () => jawab(i),
      disabled: !!pemenangRonde || youLock || pilih !== null,
      delay: i * .04
    });
  })), youLock && !pemenangRonde && /*#__PURE__*/React.createElement("div", {
    className: "gf-shake",
    style: {
      marginTop: 10,
      textAlign: "center",
      fontWeight: 700,
      fontSize: 13,
      color: P.red
    }
  }, "Jawabanmu salah — terkunci ronde ini ✗"));
}

/* ════════════════════════════════════════════════════════════
   GAME 2: SUSUN AYAT
════════════════════════════════════════════════════════════ */
function WordArea({
  kata,
  onKlik,
  disabled,
  accent
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexWrap: "wrap",
      gap: 7,
      minHeight: 48,
      padding: "10px 12px",
      borderRadius: 14,
      border: "1px dashed rgba(255,255,255,0.18)",
      background: "rgba(255,255,255,0.03)"
    }
  }, kata.map((k, i) => /*#__PURE__*/React.createElement("button", {
    key: i,
    onClick: () => !disabled && onKlik(i),
    disabled: disabled,
    className: "gf-btn",
    style: {
      padding: "8px 14px",
      borderRadius: 10,
      border: `1px solid ${accent}55`,
      background: `${accent}15`,
      color: P.cream,
      fontSize: 14,
      fontWeight: 700,
      cursor: disabled ? "default" : "pointer"
    }
  }, k)), !kata.length && /*#__PURE__*/React.createElement("span", {
    style: {
      color: P.muted,
      fontSize: 13,
      fontWeight: 600
    }
  }, "Tap kata di bawah untuk menyusun…"));
}
function SusunSolo({
  onExit
}) {
  const [ayat] = useState(() => siapkanAyat(5));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(0);
  const [benarTotal, setBenarTotal] = useState(0);
  const [disusun, setDisusun] = useState([]);
  const [bank, setBank] = useState([]);
  const [waktu, setWaktu] = useState(90);
  const [selesai, setSelesai] = useState(false);
  const [flash, setFlash] = useState(null);
  const [streak, setStreak] = useState(0);
  const timerRef = useRef();
  const a = ayat[idx];
  useEffect(() => {
    setBank(a.acak.map((k, i) => ({
      kata: k,
      origIdx: i
    })));
    setDisusun([]);
  }, [idx]);
  const lanjut = useCallback(berhasil => {
    clearInterval(timerRef.current);
    if (berhasil) {
      const p = 100 + Math.round(waktu / 90 * 150) + streak * 25;
      setSkor(s => s + p);
      setBenarTotal(b => b + 1);
      setStreak(s => s + 1);
      setFlash({
        ok: true,
        pesan: `+${p} poin!`
      });
    } else {
      setStreak(0);
      setFlash({
        ok: false,
        pesan: "Waktu habis ⏱"
      });
    }
    setTimeout(() => {
      setFlash(null);
      if (idx + 1 >= ayat.length) setSelesai(true);else {
        setIdx(i => i + 1);
        setWaktu(90);
      }
    }, 1400);
  }, [waktu, streak, idx, ayat.length]);
  useEffect(() => {
    if (selesai || flash) return;
    timerRef.current = setInterval(() => {
      setWaktu(w => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          lanjut(false);
          return 0;
        }
        return +(w - .1).toFixed(1);
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, selesai, flash, lanjut]);
  const tambah = i => {
    if (flash) return;
    const item = bank[i];
    const nd = [...disusun, item];
    setDisusun(nd);
    setBank(b => b.filter((_, j) => j !== i));
    if (nd.length === a.kata.length) {
      const benar = nd.every((it, j) => it.kata === a.kata[j]);
      if (benar) lanjut(true);else setFlash({
        ok: false,
        pesan: "Susunan belum tepat 🤔"
      });
    }
  };
  const hapus = i => {
    const item = disusun[i];
    setDisusun(d => d.filter((_, j) => j !== i));
    setBank(b => [...b, item]);
    setFlash(null);
  };
  if (selesai) return /*#__PURE__*/React.createElement(Hasil, {
    judul: "Selesai! 📖",
    skor: skor,
    accent: P.p2,
    onExit: onExit,
    baris: [{
      label: "Ayat tersusun",
      val: `${benarTotal}/5`
    }, {
      label: "Streak terbaik",
      val: `${Math.max(benarTotal, 0)} 🔥`
    }, {
      label: "Total poin",
      val: skor.toLocaleString()
    }]
  });
  const ratio = waktu / 90;
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 28px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement(Pill, {
    color: P.p2,
    label: `${skor} pts`
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 14,
      marginBottom: 16
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      fontSize: 12.5,
      fontWeight: 700,
      color: P.muted,
      marginBottom: 6
    }
  }, /*#__PURE__*/React.createElement("span", null, "Ayat ", idx + 1, "/5 · ", /*#__PURE__*/React.createElement("span", {
    style: {
      color: P.p2
    }
  }, a.ref)), /*#__PURE__*/React.createElement("span", {
    style: {
      color: ratio < .3 ? P.red : P.p2
    }
  }, Math.ceil(waktu), " dtk")), /*#__PURE__*/React.createElement("div", {
    style: {
      height: 5,
      background: "rgba(255,255,255,0.1)",
      borderRadius: 99
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      height: "100%",
      width: `${ratio * 100}%`,
      background: ratio < .3 ? P.red : P.p2,
      transition: "width .1s linear"
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 700,
      color: P.p2,
      letterSpacing: 1,
      textTransform: "uppercase",
      marginBottom: 8
    }
  }, "Susunanmu:"), /*#__PURE__*/React.createElement(WordArea, {
    kata: disusun.map(x => x.kata),
    onKlik: hapus,
    disabled: !!flash,
    accent: P.p2
  })), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 700,
      color: P.muted,
      letterSpacing: 1,
      textTransform: "uppercase",
      marginBottom: 8
    }
  }, "Bank Kata:"), /*#__PURE__*/React.createElement(WordArea, {
    kata: bank.map(x => x.kata),
    onKlik: tambah,
    disabled: !!flash,
    accent: P.muted
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      minHeight: 30,
      marginTop: 12,
      textAlign: "center"
    }
  }, flash && /*#__PURE__*/React.createElement("div", {
    className: "gf-pop",
    style: {
      fontWeight: 800,
      fontSize: 16,
      color: flash.ok ? P.green : P.red,
      fontFamily: "'Bricolage Grotesque',sans-serif"
    }
  }, flash.pesan)));
}
function SusunTatap({
  lawan,
  onExit
}) {
  const [ayat] = useState(() => siapkanAyat(5));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({
    p1: 0,
    p2: 0
  });
  const [state, setState] = useState({
    p1: {
      disusun: [],
      bank: []
    },
    p2: {
      disusun: [],
      bank: []
    }
  });
  const [waktu, setWaktu] = useState(90);
  const [winner, setWinner] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const a = ayat[idx];
  useEffect(() => {
    const bank = a.acak.map((k, i) => ({
      kata: k,
      origIdx: i
    }));
    setState({
      p1: {
        disusun: [],
        bank: [...bank]
      },
      p2: {
        disusun: [],
        bank: [...bank]
      }
    });
  }, [idx]);
  const lanjut = useCallback(w => {
    if (idx + 1 >= ayat.length) setSelesai(true);else {
      setIdx(i => i + 1);
      setWinner(null);
      setWaktu(90);
    }
    ;
    if (w) setSkor(s => ({
      ...s,
      [w]: s[w] + 100 + Math.round(waktu / 90 * 80)
    }));
  }, [idx, ayat.length, waktu]);
  useEffect(() => {
    if (selesai || winner) return;
    timerRef.current = setInterval(() => {
      setWaktu(w => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          setWinner("seri");
          setTimeout(() => lanjut(null), 1500);
          return 0;
        }
        return +(w - .1).toFixed(1);
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, winner, selesai, lanjut]);
  const tambah = (pem, i) => {
    if (winner) return;
    const cur = state[pem];
    const item = cur.bank[i];
    const nd = [...cur.disusun, item];
    const nb = cur.bank.filter((_, j) => j !== i);
    setState(st => ({
      ...st,
      [pem]: {
        disusun: nd,
        bank: nb
      }
    }));
    if (nd.length === a.kata.length && nd.every((it, j) => it.kata === a.kata[j])) {
      clearInterval(timerRef.current);
      setWinner(pem);
      setTimeout(() => lanjut(pem), 1500);
    }
  };
  const hapus = (pem, i) => {
    if (winner) return;
    setState(st => {
      const cur = st[pem];
      const item = cur.disusun[i];
      return {
        ...st,
        [pem]: {
          disusun: cur.disusun.filter((_, j) => j !== i),
          bank: [...cur.bank, item]
        }
      };
    });
  };
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! 🏆" : `${lawan.nama} Menang! 🏆`;
    return /*#__PURE__*/React.createElement(Hasil, {
      judul: w,
      skor: null,
      accent: skor.p1 >= skor.p2 ? P.p1 : P.p2,
      onExit: onExit,
      custom: /*#__PURE__*/React.createElement("div", {
        style: {
          display: "flex",
          gap: 12,
          justifyContent: "center",
          marginTop: 8
        }
      }, /*#__PURE__*/React.createElement(ScorePill, {
        name: "Kamu",
        val: skor.p1,
        color: P.p1,
        win: skor.p1 >= skor.p2
      }), /*#__PURE__*/React.createElement(ScorePill, {
        name: lawan.nama,
        val: skor.p2,
        color: P.p2,
        win: skor.p2 >= skor.p1
      }))
    });
  }
  const ratio = waktu / 90;
  const Panel = ({
    pem,
    nama,
    color,
    flip
  }) => /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      padding: "12px 16px",
      display: "flex",
      flexDirection: "column",
      transform: flip ? "rotate(180deg)" : "none",
      background: winner === pem ? `${color}14` : "transparent"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
      marginBottom: 8
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 6
    }
  }, /*#__PURE__*/React.createElement(Avatar, {
    nama: nama,
    size: 24
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontWeight: 800,
      fontSize: 13,
      color
    }
  }, nama)), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 17,
      color
    }
  }, skor[pem])), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      fontWeight: 700,
      color: P.muted,
      marginBottom: 5
    }
  }, a.ref), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      color: P.muted,
      marginBottom: 7
    }
  }, "Susunanmu:", /*#__PURE__*/React.createElement("br", null), /*#__PURE__*/React.createElement(WordArea, {
    kata: state[pem].disusun.map(x => x.kata),
    onKlik: i => hapus(pem, i),
    disabled: !!winner,
    accent: color
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      color: P.muted
    }
  }, "Bank Kata:", /*#__PURE__*/React.createElement("br", null), /*#__PURE__*/React.createElement(WordArea, {
    kata: state[pem].bank.map(x => x.kata),
    onKlik: i => tambah(pem, i),
    disabled: !!winner,
    accent: P.muted
  })), winner === pem && /*#__PURE__*/React.createElement("div", {
    className: "gf-pop",
    style: {
      textAlign: "center",
      marginTop: 6,
      fontWeight: 800,
      color,
      fontSize: 13
    }
  }, "Tersusun! 🎉"));
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      minHeight: 560
    }
  }, /*#__PURE__*/React.createElement(Panel, {
    pem: "p2",
    nama: lawan.nama,
    color: P.p2,
    flip: true
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "7px 14px",
      background: "rgba(0,0,0,0.3)",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      gap: 12,
      position: "relative"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: {
      ...gBtn,
      padding: "4px 10px",
      position: "absolute",
      left: 8
    }
  }, "←"), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 11,
      fontWeight: 800,
      color: P.muted
    }
  }, "RONDE ", idx + 1, "/", ayat.length), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "grid",
      placeItems: "center"
    }
  }, /*#__PURE__*/React.createElement(TimerRing, {
    ratio: ratio,
    danger: ratio < .3,
    size: 44
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      fontWeight: 800,
      fontSize: 13,
      color: ratio < .3 ? P.red : P.cream
    }
  }, Math.ceil(waktu)))), /*#__PURE__*/React.createElement(Panel, {
    pem: "p1",
    nama: "Kamu",
    color: P.p1
  }));
}
function SusunOnline({
  players,
  sessionCode,
  hindariKeys,
  hostId,
  onExit
}) {
  const myId = window.__GAME_USER__?.id;
  const [ayat] = useState(() => siapkanAyatSeed(5, sessionCode + ":susun", hindariKeys));
  useEffect(() => {
    if (myId === hostId) apiPost("/game/record-questions", {
      session_code: sessionCode,
      keys: keyDariAyat(ayat)
    }).catch(() => {});
  }, []);
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(() => Object.fromEntries(players.map(p => [p.id, 0])));
  const skorRef = useRef(skor);
  const [disusun, setDisusun] = useState([]);
  const [bank, setBank] = useState([]);
  const [waktu, setWaktu] = useState(90);
  const [pemenangRonde, setPemenangRonde] = useState(null); // {id,nama} atau "seri" atau null
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const resolvedRef = useRef(false);
  const waktuRef = useRef(90);
  const a = ayat[idx];
  useEffect(() => {
    skorRef.current = skor;
  }, [skor]);
  const lanjutKe = useCallback(nextIdx => {
    if (nextIdx >= ayat.length) setSelesai(true);else {
      setIdx(nextIdx);
      setPemenangRonde(null);
      setWaktu(90);
    }
  }, [ayat.length]);
  const {
    sendMove,
    sendFinished,
    keluarDariSesi,
    pemainKeluar,
    pemainDitandaiKeluar,
    sesiDiakhiriKarenaKeluar,
    putuskanKelanjutan,
    reaksiMasuk,
    kirimReaksi
  } = useOnlineGame(sessionCode, d => {
    const p = d.payload || {};
    if (p.type === "ronde_selesai" && !resolvedRef.current) {
      resolvedRef.current = true;
      clearInterval(timerRef.current);
      const pemain = players.find(pl => pl.id === d.user_id);
      setPemenangRonde(pemain || {
        id: d.user_id,
        nama: "?"
      });
      if (d.scores) setSkor(d.scores);
      setTimeout(() => lanjutKe(idx + 1), 1600);
    }
  }, d => {
    setSkor(Object.fromEntries(d.players.map(p => [p.user_id, p.score])));
    setSelesai(true);
  });
  useEffect(() => {
    if (sesiDiakhiriKarenaKeluar) onExit();
  }, [sesiDiakhiriKarenaKeluar]);
  useEffect(() => {
    setBank(a.acak.map((k, i) => ({
      kata: k,
      origIdx: i
    })));
    setDisusun([]);
    resolvedRef.current = false;
    waktuRef.current = 90;
  }, [idx]);
  useEffect(() => {
    if (selesai || pemenangRonde) return;
    timerRef.current = setInterval(() => {
      setWaktu(w => {
        const n = w <= 0.1 ? 0 : +(w - .1).toFixed(1);
        waktuRef.current = n;
        if (n === 0 && !resolvedRef.current) {
          resolvedRef.current = true;
          setPemenangRonde("seri");
          sendMove({
            type: "ronde_selesai",
            ronde: idx
          });
          setTimeout(() => lanjutKe(idx + 1), 1600);
        }
        return n;
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, selesai, pemenangRonde, lanjutKe, sendMove]);
  const tambah = i => {
    if (pemenangRonde) return;
    const item = bank[i];
    const nd = [...disusun, item];
    setDisusun(nd);
    setBank(b => b.filter((_, j) => j !== i));
    if (nd.length === a.kata.length && nd.every((it, j) => it.kata === a.kata[j])) {
      if (!resolvedRef.current) {
        resolvedRef.current = true;
        clearInterval(timerRef.current);
        const p = 100 + Math.round(waktuRef.current / 90 * 150);
        const skorBaru = (skorRef.current[myId] || 0) + p;
        setSkor(s => ({
          ...s,
          [myId]: skorBaru
        }));
        setPemenangRonde({
          id: myId,
          nama: "Kamu"
        });
        sendMove({
          type: "ronde_selesai",
          ronde: idx,
          score: skorBaru
        });
        setTimeout(() => lanjutKe(idx + 1), 1600);
      }
    }
  };
  const hapus = i => {
    if (pemenangRonde) return;
    const item = disusun[i];
    setDisusun(d => d.filter((_, j) => j !== i));
    setBank(b => [...b, item]);
  };
  useEffect(() => {
    if (selesai) sendFinished(skorRef.current[myId] || 0);
  }, [selesai]);
  if (selesai) return /*#__PURE__*/React.createElement(HasilN, {
    players: players,
    scores: skor,
    myId: myId,
    accent: P.p2,
    onExit: onExit
  });
  const ratio = waktu / 90;
  const statusTeks = pemenangRonde === "seri" ? "Waktu habis — ronde seri" : pemenangRonde ? pemenangRonde.id === myId ? "Kamu berhasil duluan! 🎉" : `${pemenangRonde.nama} lebih cepat…` : "Susun secepat mungkin!";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 26px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, pemainKeluar && /*#__PURE__*/React.createElement(DialogPemainKeluar, {
    nama: pemainKeluar.nama,
    onLanjut: () => putuskanKelanjutan("continue"),
    onAkhiri: () => putuskanKelanjutan("end")
  }), /*#__PURE__*/React.createElement(EmojiReactionBar, {
    onKirim: kirimReaksi
  }), /*#__PURE__*/React.createElement(ReactionOverlay, {
    reaksi: reaksiMasuk,
    players: players
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => {
      keluarDariSesi();
      onExit();
    },
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "grid",
      placeItems: "center"
    }
  }, /*#__PURE__*/React.createElement(TimerRing, {
    ratio: ratio,
    danger: ratio < .3,
    size: 42
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      fontWeight: 800,
      fontSize: 13,
      color: ratio < .3 ? P.red : P.cream
    }
  }, Math.ceil(waktu)))), /*#__PURE__*/React.createElement(PapanSkorN, {
    players: players,
    scores: skor,
    myId: myId,
    pemainKeluarId: pemainDitandaiKeluar
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 10,
      padding: "8px 12px",
      borderRadius: 10,
      background: "rgba(255,255,255,0.04)",
      fontSize: 13,
      fontWeight: 700,
      color: pemenangRonde && pemenangRonde !== "seri" ? pemenangRonde.id === myId ? P.green : P.p2 : P.muted
    }
  }, statusTeks), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 14,
      fontSize: 12,
      fontWeight: 700,
      color: P.p2,
      letterSpacing: 1
    }
  }, a.ref), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 8,
      marginBottom: 8
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      color: P.muted,
      marginBottom: 6
    }
  }, "Susunanmu:"), /*#__PURE__*/React.createElement(WordArea, {
    kata: disusun.map(x => x.kata),
    onKlik: hapus,
    disabled: !!pemenangRonde,
    accent: P.p2
  })), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      color: P.muted,
      marginBottom: 6
    }
  }, "Bank Kata:"), /*#__PURE__*/React.createElement(WordArea, {
    kata: bank.map(x => x.kata),
    onKlik: tambah,
    disabled: !!pemenangRonde,
    accent: P.muted
  })));
}

/* ════════════════════════════════════════════════════════════
   GAME 3: TEBAK TOKOH
════════════════════════════════════════════════════════════ */
const TEBAK_POIN = [400, 280, 180, 100];
function TebakSolo({
  onExit
}) {
  const [tokoh] = useState(() => siapkanTokoh(10));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(0);
  const [benarTotal, setBenarTotal] = useState(0);
  const [clueIdx, setClueIdx] = useState(0);
  const [pilih, setPilih] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const t = tokoh[idx];
  const lanjut = useCallback(() => {
    if (idx + 1 >= tokoh.length) setSelesai(true);else {
      setIdx(i => i + 1);
      setClueIdx(0);
      setPilih(null);
    }
  }, [idx, tokoh.length]);
  const jawab = i => {
    if (pilih !== null) return;
    setPilih(i);
    if (t.opsi[i] === t.jawaban) {
      const p = TEBAK_POIN[Math.min(clueIdx, TEBAK_POIN.length - 1)];
      setSkor(s => s + p);
      setBenarTotal(b => b + 1);
    }
    setTimeout(lanjut, 1600);
  };
  if (selesai) return /*#__PURE__*/React.createElement(Hasil, {
    judul: "Selesai! 🔍",
    skor: skor,
    accent: P.purple,
    onExit: onExit,
    baris: [{
      label: "Tokoh tertebak",
      val: `${benarTotal}/${tokoh.length}`
    }, {
      label: "Akurasi",
      val: `${Math.round(benarTotal / tokoh.length * 100)}%`
    }, {
      label: "Total poin",
      val: skor.toLocaleString()
    }]
  });
  const maxPoin = TEBAK_POIN[Math.min(clueIdx, TEBAK_POIN.length - 1)];
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 28px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement(Pill, {
    color: P.purple,
    label: `${skor} pts`
  })), /*#__PURE__*/React.createElement("div", {
    key: idx,
    className: "gf-pop",
    style: {
      marginTop: 20,
      padding: "20px",
      borderRadius: 20,
      background: "rgba(167,139,250,0.08)",
      border: "1px solid rgba(167,139,250,0.2)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 700,
      color: P.purple,
      letterSpacing: 1,
      textTransform: "uppercase"
    }
  }, "Siapa Aku? · Tokoh ", idx + 1, "/", tokoh.length), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 12,
      display: "grid",
      gap: 8
    }
  }, t.clues.slice(0, clueIdx + 1).map((c, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      gap: 9,
      alignItems: "flex-start"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: P.purple,
      fontWeight: 800,
      flexShrink: 0
    }
  }, "#", i + 1), /*#__PURE__*/React.createElement("span", {
    style: {
      fontWeight: 600,
      fontSize: 15,
      color: P.cream,
      lineHeight: 1.4
    }
  }, c)))), clueIdx < t.clues.length - 1 && pilih === null && /*#__PURE__*/React.createElement("button", {
    onClick: () => setClueIdx(i => i + 1),
    className: "gf-btn",
    style: {
      marginTop: 14,
      width: "100%",
      padding: "10px",
      borderRadius: 12,
      border: `1px solid ${P.purple}55`,
      background: `${P.purple}15`,
      color: P.purple,
      fontWeight: 700,
      fontSize: 13
    }
  }, "Buka Clue Berikutnya (-", TEBAK_POIN[Math.min(clueIdx, TEBAK_POIN.length - 2)] - TEBAK_POIN[Math.min(clueIdx + 1, TEBAK_POIN.length - 1)], " poin)")), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 16
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 700,
      color: P.muted,
      marginBottom: 8
    }
  }, "Siapa tokoh ini? (Nilai maks: ", /*#__PURE__*/React.createElement("span", {
    style: {
      color: P.purple
    }
  }, maxPoin), ")"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 9
    }
  }, t.opsi.map((op, i) => {
    let st = "idle";
    if (pilih !== null) {
      st = op === t.jawaban ? "benar" : i === pilih ? "salah" : "redup";
    }
    return /*#__PURE__*/React.createElement(OptBtn, {
      key: i,
      text: op,
      idx: i,
      state: st,
      onClick: () => jawab(i),
      disabled: pilih !== null,
      delay: i * .05
    });
  }))));
}
function TebakTatap({
  lawan,
  onExit
}) {
  const [tokoh] = useState(() => siapkanTokoh(10));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({
    p1: 0,
    p2: 0
  });
  const [clueIdx, setClueIdx] = useState(0);
  const [buzzed, setBuzzed] = useState(null);
  const [lock, setLock] = useState({
    p1: false,
    p2: false
  });
  const [pilih, setPilih] = useState({
    p1: null,
    p2: null
  });
  const [winner, setWinner] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const lockTimers = useRef({});
  const t = tokoh[idx];
  const lanjut = useCallback(w => {
    if (idx + 1 >= tokoh.length) setSelesai(true);else {
      setIdx(i => i + 1);
      setClueIdx(0);
      setBuzzed(null);
      setLock({
        p1: false,
        p2: false
      });
      setPilih({
        p1: null,
        p2: null
      });
      setWinner(null);
    }
    if (w) setSkor(s => ({
      ...s,
      [w]: s[w] + TEBAK_POIN[Math.min(clueIdx, 3)]
    }));
  }, [idx, tokoh.length, clueIdx]);
  const buzz = pem => {
    if (buzzed || lock[pem]) return;
    setBuzzed(pem);
  };
  const jawab = (pem, i) => {
    if (buzzed !== pem || pilih[pem] !== null) return;
    setPilih(p => ({
      ...p,
      [pem]: i
    }));
    if (t.opsi[i] === t.jawaban) {
      setWinner(pem);
      setTimeout(() => lanjut(pem), 1500);
    } else {
      setBuzzed(null);
      setLock(l => ({
        ...l,
        [pem]: true
      }));
      lockTimers.current[pem] = setTimeout(() => {
        setLock(l => ({
          ...l,
          [pem]: false
        }));
      }, 5000);
    }
  };
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! 🏆" : `${lawan.nama} Menang! 🏆`;
    return /*#__PURE__*/React.createElement(Hasil, {
      judul: w,
      skor: null,
      accent: skor.p1 >= skor.p2 ? P.p1 : P.p2,
      onExit: onExit,
      custom: /*#__PURE__*/React.createElement("div", {
        style: {
          display: "flex",
          gap: 12,
          justifyContent: "center",
          marginTop: 8
        }
      }, /*#__PURE__*/React.createElement(ScorePill, {
        name: "Kamu",
        val: skor.p1,
        color: P.p1,
        win: skor.p1 >= skor.p2
      }), /*#__PURE__*/React.createElement(ScorePill, {
        name: lawan.nama,
        val: skor.p2,
        color: P.p2,
        win: skor.p2 >= skor.p1
      }))
    });
  }
  const Panel = ({
    pem,
    nama,
    color,
    flip
  }) => {
    const isBuzzed = buzzed === pem;
    const isLocked = lock[pem];
    return /*#__PURE__*/React.createElement("div", {
      style: {
        flex: 1,
        padding: "12px 16px",
        display: "flex",
        flexDirection: "column",
        transform: flip ? "rotate(180deg)" : "none",
        background: winner === pem ? `${color}14` : isBuzzed ? `${color}0a` : "transparent"
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center",
        marginBottom: 8
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        alignItems: "center",
        gap: 7
      }
    }, /*#__PURE__*/React.createElement(Avatar, {
      nama: nama,
      size: 24
    }), /*#__PURE__*/React.createElement("span", {
      style: {
        fontWeight: 800,
        fontSize: 13,
        color
      }
    }, nama)), /*#__PURE__*/React.createElement("span", {
      style: {
        fontFamily: "'Bricolage Grotesque',sans-serif",
        fontWeight: 800,
        fontSize: 17,
        color
      }
    }, skor[pem])), !isBuzzed && !winner && /*#__PURE__*/React.createElement("button", {
      onClick: () => buzz(pem),
      disabled: !!buzzed || isLocked,
      className: "gf-btn",
      style: {
        padding: "14px",
        borderRadius: 14,
        border: `2px solid ${isLocked ? "rgba(255,255,255,0.1)" : color}`,
        background: isLocked ? "rgba(255,255,255,0.03)" : `${color}22`,
        color: isLocked ? P.muted : color,
        fontWeight: 800,
        fontSize: 17,
        cursor: isLocked ? "default" : "pointer",
        transition: "all .15s"
      }
    }, isLocked ? `Dikunci sementara…` : `⚡ BUZZ!`), isBuzzed && !winner && /*#__PURE__*/React.createElement("div", {
      style: {
        display: "grid",
        gap: 7
      }
    }, t.opsi.map((op, i) => {
      const st = pilih[pem] === i ? op === t.jawaban ? "benar" : "salah" : "idle";
      return /*#__PURE__*/React.createElement("button", {
        key: i,
        onClick: () => jawab(pem, i),
        className: "gf-btn",
        style: {
          padding: "11px",
          borderRadius: 12,
          border: `1.5px solid ${st === "benar" ? P.green : st === "salah" ? P.red : `${color}44`}`,
          background: st === "benar" ? `${P.green}22` : st === "salah" ? `${P.red}1f` : `${color}10`,
          color: P.cream,
          fontSize: 13.5,
          fontWeight: 700,
          cursor: "pointer"
        }
      }, op);
    })), winner === pem && /*#__PURE__*/React.createElement("div", {
      className: "gf-pop",
      style: {
        textAlign: "center",
        marginTop: 6,
        fontWeight: 800,
        color,
        fontSize: 14
      }
    }, "Benar! +", TEBAK_POIN[Math.min(clueIdx, 3)], " poin"), isLocked && /*#__PURE__*/React.createElement("div", {
      className: "gf-shake",
      style: {
        textAlign: "center",
        marginTop: 6,
        color: P.red,
        fontSize: 12,
        fontWeight: 700
      }
    }, "Salah! Dikunci 5 dtk ✗"));
  };
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      minHeight: 560
    }
  }, /*#__PURE__*/React.createElement(Panel, {
    pem: "p2",
    nama: lawan.nama,
    color: P.p2,
    flip: true
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      background: "rgba(0,0,0,0.3)",
      padding: "8px 14px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      gap: 12,
      position: "relative"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: {
      ...gBtn,
      padding: "4px 10px",
      position: "absolute",
      left: 0
    }
  }, "←"), /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      fontWeight: 800,
      color: P.muted,
      letterSpacing: 1
    }
  }, "TOKOH ", idx + 1, "/", tokoh.length))), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 8,
      padding: "10px 12px",
      borderRadius: 14,
      background: "rgba(167,139,250,0.08)",
      border: "1px solid rgba(167,139,250,0.2)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 11,
      fontWeight: 700,
      color: P.purple,
      marginBottom: 6
    }
  }, "Clue terlihat semua pemain:"), t.clues.slice(0, clueIdx + 1).map((c, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      fontSize: 13,
      color: P.cream,
      fontWeight: 600,
      marginBottom: 4
    }
  }, "#", i + 1, " ", c)), clueIdx < t.clues.length - 1 && !winner && /*#__PURE__*/React.createElement("button", {
    onClick: () => setClueIdx(i => i + 1),
    className: "gf-btn",
    style: {
      marginTop: 8,
      width: "100%",
      padding: "8px",
      borderRadius: 10,
      border: `1px solid ${P.purple}44`,
      background: `${P.purple}10`,
      color: P.purple,
      fontWeight: 700,
      fontSize: 12
    }
  }, "Buka Clue Berikutnya"))), /*#__PURE__*/React.createElement(Panel, {
    pem: "p1",
    nama: "Kamu",
    color: P.p1
  }));
}
function TebakOnline({
  players,
  sessionCode,
  hindariKeys,
  hostId,
  onExit
}) {
  const myId = window.__GAME_USER__?.id;
  const [tokoh] = useState(() => siapkanTokohSeed(10, sessionCode + ":tebak", hindariKeys));
  useEffect(() => {
    if (myId === hostId) apiPost("/game/record-questions", {
      session_code: sessionCode,
      keys: keyDariTokoh(tokoh)
    }).catch(() => {});
  }, []);
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(() => Object.fromEntries(players.map(p => [p.id, 0])));
  const skorRef = useRef(skor);
  const [clueIdx, setClueIdx] = useState(0);
  const [pilih, setPilih] = useState(null);
  const [youLock, setYouLock] = useState(false);
  const [pemenangRonde, setPemenangRonde] = useState(null); // {id,nama} atau "seri" atau null
  const [selesai, setSelesai] = useState(false);
  const [waktu, setWaktu] = useState(25);
  const timerRef = useRef();
  const waktuRef = useRef(25);
  const resolvedRef = useRef(false);
  const t = tokoh[idx];
  useEffect(() => {
    skorRef.current = skor;
  }, [skor]);
  const lanjut = useCallback(() => {
    if (idx + 1 >= tokoh.length) setSelesai(true);else {
      setIdx(i => i + 1);
      setClueIdx(0);
      setPilih(null);
      setYouLock(false);
      setPemenangRonde(null);
    }
  }, [idx, tokoh.length]);
  const {
    sendMove,
    sendFinished,
    keluarDariSesi,
    pemainKeluar,
    pemainDitandaiKeluar,
    sesiDiakhiriKarenaKeluar,
    putuskanKelanjutan,
    reaksiMasuk,
    kirimReaksi
  } = useOnlineGame(sessionCode, d => {
    const p = d.payload || {};
    if (p.type === "answered_correct" && !resolvedRef.current) {
      resolvedRef.current = true;
      clearInterval(timerRef.current);
      const pemain = players.find(pl => pl.id === d.user_id);
      setPemenangRonde(pemain || {
        id: d.user_id,
        nama: "?"
      });
      if (d.scores) setSkor(d.scores);
      setTimeout(lanjut, 1600);
    }
    if (p.type === "round_timeout" && p.ronde === idx && !resolvedRef.current) {
      resolvedRef.current = true;
      clearInterval(timerRef.current);
      setPemenangRonde("seri");
      setTimeout(lanjut, 1700);
    }
  }, d => {
    setSkor(Object.fromEntries(d.players.map(p => [p.user_id, p.score])));
    setSelesai(true);
  });
  useEffect(() => {
    if (sesiDiakhiriKarenaKeluar) onExit();
  }, [sesiDiakhiriKarenaKeluar]);
  useEffect(() => {
    resolvedRef.current = false;
    waktuRef.current = 25;
    setWaktu(25);
  }, [idx]);

  // Timer per ronde — kalau tidak ada yang jawab benar sampai waktu habis
  // (mis. semua pemain jawab salah), ronde SEBELUMNYA stuck selamanya
  // menunggu jawaban benar yang tidak akan pernah datang. Auto-resolve
  // ke "seri" supaya game selalu lanjut ke tokoh berikutnya.
  //
  // PENTING: tiap client menjalankan setInterval sendiri-sendiri (tidak ada
  // jam server bersama), jadi timer di device yang berbeda TIDAK dijamin
  // menyentuh nol di tick yang sama (background tab throttling dkk). Kalau
  // kedua sisi cuma resolve "seri" secara lokal tanpa saling kabari, sisi
  // yang timer-nya sedikit lebih lambat tidak akan pernah tahu ronde sudah
  // berakhir dan macet selamanya menunggu event yang tak kunjung datang.
  // Solusi: HANYA host yang broadcast round_timeout (mencegah race 2 sisi
  // saling kirim barengan); kedua sisi (termasuk host sendiri) baru
  // mengubah state lewat handler round_timeout di useOnlineGame di atas,
  // sama seperti pola answered_correct.
  useEffect(() => {
    if (selesai) return;
    timerRef.current = setInterval(() => {
      setWaktu(w => {
        const n = w <= 0.1 ? 0 : +(w - .1).toFixed(1);
        waktuRef.current = n;
        if (n === 0 && !resolvedRef.current && myId === hostId) {
          sendMove({
            type: "round_timeout",
            ronde: idx
          });
        }
        return n;
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, selesai, lanjut]);
  const jawab = i => {
    if (resolvedRef.current || pilih !== null) return;
    setPilih(i);
    if (t.opsi[i] === t.jawaban) {
      resolvedRef.current = true;
      clearInterval(timerRef.current);
      const p = TEBAK_POIN[Math.min(clueIdx, 3)];
      const skorBaru = (skorRef.current[myId] || 0) + p;
      setSkor(s => ({
        ...s,
        [myId]: skorBaru
      }));
      setPemenangRonde({
        id: myId,
        nama: "Kamu"
      });
      sendMove({
        type: "answered_correct",
        ronde: idx,
        score: skorBaru
      });
      setTimeout(lanjut, 1600);
    } else {
      setYouLock(true);
      sendMove({
        type: "answered_wrong",
        ronde: idx
      });
    }
  };
  useEffect(() => {
    if (selesai) sendFinished(skorRef.current[myId] || 0);
  }, [selesai]);
  if (selesai) return /*#__PURE__*/React.createElement(HasilN, {
    players: players,
    scores: skor,
    myId: myId,
    accent: P.purple,
    onExit: onExit
  });
  const statusTeks = pemenangRonde === "seri" ? "Waktu habis — tidak ada yang benar" : pemenangRonde ? pemenangRonde.id === myId ? "Kamu benar duluan! 🎉" : `${pemenangRonde.nama} lebih cepat…` : "Siapa yang jawab duluan?";
  const ratio = waktu / 25;
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 26px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, pemainKeluar && /*#__PURE__*/React.createElement(DialogPemainKeluar, {
    nama: pemainKeluar.nama,
    onLanjut: () => putuskanKelanjutan("continue"),
    onAkhiri: () => putuskanKelanjutan("end")
  }), /*#__PURE__*/React.createElement(EmojiReactionBar, {
    onKirim: kirimReaksi
  }), /*#__PURE__*/React.createElement(ReactionOverlay, {
    reaksi: reaksiMasuk,
    players: players
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => {
      keluarDariSesi();
      onExit();
    },
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      display: "grid",
      placeItems: "center"
    }
  }, /*#__PURE__*/React.createElement(TimerRing, {
    ratio: ratio,
    danger: ratio < .3,
    size: 42
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      fontWeight: 800,
      fontSize: 13,
      color: ratio < .3 ? P.red : P.cream
    }
  }, Math.ceil(waktu)))), /*#__PURE__*/React.createElement(PapanSkorN, {
    players: players,
    scores: skor,
    myId: myId,
    pemainKeluarId: pemainDitandaiKeluar
  }), /*#__PURE__*/React.createElement("div", {
    key: idx,
    style: {
      marginTop: 16,
      padding: "16px",
      borderRadius: 18,
      background: "rgba(167,139,250,0.08)",
      border: "1px solid rgba(167,139,250,0.2)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 700,
      color: P.purple,
      letterSpacing: 1,
      marginBottom: 10
    }
  }, "SIAPA AKU? · Tokoh ", idx + 1, "/", tokoh.length), t.clues.slice(0, clueIdx + 1).map((c, i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      gap: 8,
      marginBottom: 7
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: P.purple,
      fontWeight: 800
    }
  }, "#", i + 1), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 15,
      fontWeight: 600,
      color: P.cream,
      lineHeight: 1.4
    }
  }, c))), clueIdx < t.clues.length - 1 && !pemenangRonde && /*#__PURE__*/React.createElement("button", {
    onClick: () => setClueIdx(i => i + 1),
    className: "gf-btn",
    style: {
      marginTop: 10,
      width: "100%",
      padding: "9px",
      borderRadius: 12,
      border: `1px solid ${P.purple}44`,
      background: `${P.purple}12`,
      color: P.purple,
      fontWeight: 700,
      fontSize: 13
    }
  }, "Buka Clue Berikutnya")), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 12,
      padding: "8px 12px",
      borderRadius: 10,
      background: "rgba(255,255,255,0.04)",
      fontSize: 13,
      fontWeight: 700,
      color: pemenangRonde ? pemenangRonde.id === myId ? P.green : P.p2 : P.muted
    }
  }, statusTeks), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 9,
      marginTop: 14
    }
  }, t.opsi.map((op, i) => {
    let st = "idle";
    if (pemenangRonde || youLock) {
      st = op === t.jawaban ? "benar" : i === pilih ? "salah" : "redup";
    } else if (i === pilih) st = "salah";
    return /*#__PURE__*/React.createElement(OptBtn, {
      key: i,
      text: op,
      idx: i,
      state: st,
      onClick: () => jawab(i),
      disabled: !!pemenangRonde || youLock || pilih !== null,
      delay: i * .04
    });
  })), youLock && !pemenangRonde && /*#__PURE__*/React.createElement("div", {
    className: "gf-shake",
    style: {
      textAlign: "center",
      marginTop: 8,
      color: P.red,
      fontSize: 13,
      fontWeight: 700
    }
  }, "Jawaban salah ✗"));
}

/* ════════════════════════════════════════════════════════════
   GAME 4: MEMORY MATCH
════════════════════════════════════════════════════════════ */
// Level 1: 4x4 (8 pasang). Level 2: 8x8 (32 pasang), tanpa reshuffle.
// Level 3: 8x8 (32 pasang) + reshuffle kartu yang belum ketemu tiap 3
// giliran gagal (juga preview semua kartu 3 detik di awal permainan).
const MEMORY_LEVEL_CFG = {
  1: {
    pasang: 8,
    kolom: 4
  },
  2: {
    pasang: 32,
    kolom: 8
  },
  3: {
    pasang: 32,
    kolom: 8,
    reshuffle: true
  }
};
function cfgLevel(level) {
  return MEMORY_LEVEL_CFG[level] || MEMORY_LEVEL_CFG[1];
}
function KartuView({
  kartu,
  terbuka,
  matched,
  onClick,
  disabled,
  kecil
}) {
  const show = terbuka || matched;
  return /*#__PURE__*/React.createElement("div", {
    onClick: disabled || matched ? undefined : onClick,
    className: "gf-card-wrap",
    style: {
      cursor: disabled || matched ? "default" : "pointer",
      height: kecil ? 52 : 80
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: `gf-card-inner${show ? " flipped" : ""}`,
    style: {
      height: "100%"
    }
  }, /*#__PURE__*/React.createElement("div", {
    className: "gf-card-face",
    style: {
      background: "rgba(255,255,255,0.06)",
      border: "1.5px solid rgba(255,255,255,0.12)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: kecil ? 15 : 22
    }
  }, "✦")), /*#__PURE__*/React.createElement("div", {
    className: "gf-card-face gf-card-back",
    style: {
      background: matched ? "rgba(74,222,128,0.15)" : "rgba(37,99,235,0.15)",
      border: `1.5px solid ${matched ? P.green : "rgba(99,102,241,0.4)"}`
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: kecil ? 9 : 11.5,
      fontWeight: 700,
      color: matched ? P.green : P.cream,
      lineHeight: 1.2,
      textAlign: "center",
      padding: kecil ? "2px" : "4px"
    }
  }, kartu.isi))));
}

/* Reshuffle level 3: acak ULANG POSISI kartu yang BELUM matched (kartu yang
   sudah matched tetap di tempat & tetap terbuka), lalu tampilkan semua
   sebentar sebelum ditutup lagi. Mengembalikan array cards baru. */
function acakUlangBelumMatched(cards, matchedIdx, seed) {
  const belum = cards.map((c, i) => ({
    c,
    i
  })).filter(({
    i
  }) => !matchedIdx.includes(i));
  const posisi = belum.map(({
    i
  }) => i);
  // seed diberikan HANYA di mode online (supaya hasil acak identik di semua
  // device tanpa koordinasi server) — mode Solo/Tatap satu perangkat, jadi
  // Math.random() biasa sudah cukup dan tidak perlu deterministik.
  const kartuAcak = seed ? shuffleSeed(belum.map(({
    c
  }) => c), buatRng(seed)) : shuffle(belum.map(({
    c
  }) => c));
  const hasil = [...cards];
  posisi.forEach((pos, j) => {
    hasil[pos] = kartuAcak[j];
  });
  return hasil;
}
function MemorySolo({
  level,
  onExit
}) {
  const cfg = cfgLevel(level);
  const [cards, setCards] = useState(() => siapkanKartu(cfg.pasang));
  const [terbuka, setTerbuka] = useState([]);
  const [matched, setMatched] = useState([]);
  const [langkah, setLangkah] = useState(0);
  const [waktu, setWaktu] = useState(0);
  const [selesai, setSelesai] = useState(false);
  const checkRef = useRef(false);
  const timerRef = useRef();
  const [gagalBerturut, setGagalBerturut] = useState(0);
  const [previewAwal, setPreviewAwal] = useState(!!cfg.reshuffle); // level 3: tampilkan semua kartu 3 detik di awal
  const matchedRef = useRef([]);
  useEffect(() => {
    matchedRef.current = matched;
  }, [matched]);
  useEffect(() => {
    if (!previewAwal) return;
    const t = setTimeout(() => setPreviewAwal(false), 3000);
    return () => clearTimeout(t);
  }, []);
  useEffect(() => {
    if (previewAwal) return;
    timerRef.current = setInterval(() => setWaktu(w => w + 1), 1000);
    return () => clearInterval(timerRef.current);
  }, [previewAwal]);
  useEffect(() => {
    if (matched.length === cards.length && cards.length > 0) {
      clearInterval(timerRef.current);
      setSelesai(true);
    }
  }, [matched, cards.length]);
  const klik = i => {
    if (previewAwal || checkRef.current || terbuka.includes(i) || matched.includes(i) || terbuka.length >= 2) return;
    const next = [...terbuka, i];
    setTerbuka(next);
    setLangkah(l => l + 1);
    if (next.length === 2) {
      checkRef.current = true;
      const [a, b] = next;
      if (cards[a].pair === cards[b].pair) {
        setMatched(m => [...m, a, b]);
        setTerbuka([]);
        checkRef.current = false;
      } else {
        setTimeout(() => {
          setTerbuka([]);
          checkRef.current = false;
          if (cfg.reshuffle) {
            setGagalBerturut(g => {
              const ng = g + 1;
              if (ng >= 3) {
                setCards(cs => acakUlangBelumMatched(cs, matchedRef.current));
                setPreviewAwal(true);
                setTimeout(() => setPreviewAwal(false), 3000);
                return 0;
              }
              return ng;
            });
          }
        }, 900);
      }
    }
  };
  const mnt = String(Math.floor(waktu / 60)).padStart(2, "0"),
    dtk = String(waktu % 60).padStart(2, "0");
  const skor = Math.max(0, cfg.pasang * 250 - langkah * 20 - waktu * 5);
  if (selesai) return /*#__PURE__*/React.createElement(Hasil, {
    judul: "Semua Cocok! 🃏",
    skor: skor,
    accent: P.orange,
    onExit: onExit,
    baris: [{
      label: "Waktu",
      val: `${mnt}:${dtk}`
    }, {
      label: "Langkah",
      val: langkah
    }, {
      label: "Pasangan",
      val: `${matched.length / 2}/${cfg.pasang}`
    }]
  });
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 28px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
      marginBottom: 14
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(Pill, {
    color: P.orange,
    label: `⏱ ${mnt}:${dtk}`
  }), /*#__PURE__*/React.createElement(Pill, {
    color: P.green,
    label: `${matched.length / 2}/${cfg.pasang} ✓`
  }))), previewAwal && /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center",
      marginBottom: 10,
      color: P.gold,
      fontWeight: 800,
      fontSize: 13
    }
  }, "Hafalkan posisi kartu… 👀"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: `repeat(${cfg.kolom},1fr)`,
      gap: cfg.kolom > 4 ? 5 : 8
    }
  }, cards.map((c, i) => /*#__PURE__*/React.createElement(KartuView, {
    key: c.id,
    kartu: c,
    kecil: cfg.kolom > 4,
    terbuka: previewAwal || terbuka.includes(i),
    matched: matched.includes(i),
    onClick: () => klik(i),
    disabled: previewAwal || terbuka.length === 2 && !terbuka.includes(i)
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 12,
      textAlign: "center",
      color: P.muted,
      fontSize: 13,
      fontWeight: 600
    }
  }, langkah, " langkah · Skor estimasi: ", skor));
}
function MemoryTatap({
  lawan,
  level,
  onExit
}) {
  const cfg = cfgLevel(level);
  const [cards, setCards] = useState(() => siapkanKartu(cfg.pasang));
  const [terbuka, setTerbuka] = useState([]);
  const [matched, setMatched] = useState([]);
  const [giliranP, setGiliranP] = useState("p1");
  const [skor, setSkor] = useState({
    p1: 0,
    p2: 0
  });
  const [langkah, setLangkah] = useState(0);
  const [selesai, setSelesai] = useState(false);
  const checkRef = useRef(false);
  const [gagalBerturut, setGagalBerturut] = useState(0);
  const [previewAwal, setPreviewAwal] = useState(!!cfg.reshuffle);
  const matchedRef = useRef([]);
  useEffect(() => {
    matchedRef.current = matched;
  }, [matched]);
  useEffect(() => {
    if (!previewAwal) return;
    const t = setTimeout(() => setPreviewAwal(false), 3000);
    return () => clearTimeout(t);
  }, []);
  useEffect(() => {
    if (matched.length === cards.length && cards.length > 0) setSelesai(true);
  }, [matched, cards.length]);
  const klik = i => {
    if (previewAwal || checkRef.current || terbuka.includes(i) || matched.includes(i) || terbuka.length >= 2) return;
    const next = [...terbuka, i];
    setTerbuka(next);
    setLangkah(l => l + 1);
    if (next.length === 2) {
      checkRef.current = true;
      const [a, b] = next;
      if (cards[a].pair === cards[b].pair) {
        setSkor(s => ({
          ...s,
          [giliranP]: s[giliranP] + 1
        }));
        setMatched(m => [...m, a, b]);
        setTerbuka([]);
        checkRef.current = false;
      } else {
        setTimeout(() => {
          setTerbuka([]);
          setGiliranP(g => g === "p1" ? "p2" : "p1");
          checkRef.current = false;
          if (cfg.reshuffle) {
            setGagalBerturut(g => {
              const ng = g + 1;
              if (ng >= 3) {
                setCards(cs => acakUlangBelumMatched(cs, matchedRef.current));
                setPreviewAwal(true);
                setTimeout(() => setPreviewAwal(false), 3000);
                return 0;
              }
              return ng;
            });
          }
        }, 900);
      }
    }
  };
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! 🏆" : `${lawan.nama} Menang! 🏆`;
    return /*#__PURE__*/React.createElement(Hasil, {
      judul: w,
      skor: null,
      accent: skor.p1 >= skor.p2 ? P.p1 : P.p2,
      onExit: onExit,
      custom: /*#__PURE__*/React.createElement("div", {
        style: {
          display: "flex",
          gap: 12,
          justifyContent: "center",
          marginTop: 8
        }
      }, /*#__PURE__*/React.createElement(ScorePill, {
        name: "Kamu",
        val: skor.p1,
        color: P.p1,
        win: skor.p1 >= skor.p2
      }), /*#__PURE__*/React.createElement(ScorePill, {
        name: lawan.nama,
        val: skor.p2,
        color: P.p2,
        win: skor.p2 >= skor.p1
      }))
    });
  }
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 28px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: onExit,
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 800,
      color: P.muted,
      letterSpacing: 1
    }
  }, "MEMORY MATCH")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      marginBottom: 12,
      padding: "10px 14px",
      borderRadius: 14,
      background: "rgba(255,255,255,0.04)",
      border: "1px solid rgba(255,255,255,0.08)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement(Avatar, {
    nama: "Kamu",
    size: 28,
    ring: giliranP === "p1" ? P.orange : undefined
  }), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 800,
      fontSize: 13,
      color: giliranP === "p1" ? P.orange : P.cream
    }
  }, "Kamu ", giliranP === "p1" ? "← giliran" : ""), /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 18,
      color: P.orange
    }
  }, skor.p1, " pasang"))), /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "right",
      display: "flex",
      alignItems: "center",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontWeight: 800,
      fontSize: 13,
      color: giliranP === "p2" ? P.p2 : P.cream
    }
  }, giliranP === "p2" ? "giliran →" : "", " ", lawan.nama), /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 18,
      color: P.p2
    }
  }, skor.p2, " pasang")), /*#__PURE__*/React.createElement(Avatar, {
    nama: lawan.nama,
    size: 28,
    ring: giliranP === "p2" ? P.p2 : undefined
  }))), previewAwal && /*#__PURE__*/React.createElement("div", {
    style: {
      textAlign: "center",
      marginBottom: 10,
      color: P.gold,
      fontWeight: 800,
      fontSize: 13
    }
  }, "Hafalkan posisi kartu… 👀"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: `repeat(${cfg.kolom},1fr)`,
      gap: cfg.kolom > 4 ? 5 : 8
    }
  }, cards.map((c, i) => /*#__PURE__*/React.createElement(KartuView, {
    key: c.id,
    kartu: c,
    kecil: cfg.kolom > 4,
    terbuka: previewAwal || terbuka.includes(i),
    matched: matched.includes(i),
    onClick: () => klik(i),
    disabled: previewAwal || terbuka.length === 2 && !terbuka.includes(i)
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 10,
      textAlign: "center",
      fontSize: 13,
      fontWeight: 700,
      color: P.muted
    }
  }, langkah, " langkah · ", matched.length / 2, "/", cfg.pasang, " pasang ditemukan"));
}
function MemoryOnline({
  players,
  sessionCode,
  hindariKeys,
  hostId,
  level,
  onExit
}) {
  const myId = window.__GAME_USER__?.id;
  const cfg = cfgLevel(level);
  const [cards, setCards] = useState(() => siapkanKartuSeed(cfg.pasang, sessionCode + ":memory", hindariKeys));
  useEffect(() => {
    if (myId === hostId) apiPost("/game/record-questions", {
      session_code: sessionCode,
      keys: keyDariKartu(cards)
    }).catch(() => {});
  }, []);
  const [terbuka, setTerbuka] = useState([]);
  const [matched, setMatched] = useState([]);
  const [giliranIdx, setGiliranIdx] = useState(0); // index ke players — sama urutannya di semua klien
  const [skor, setSkor] = useState(() => Object.fromEntries(players.map(p => [p.id, 0])));
  const [selesai, setSelesai] = useState(false);
  const [previewAwal, setPreviewAwal] = useState(!!cfg.reshuffle);
  const checkRef = useRef(false);
  const matchedRef = useRef([]);
  const skorRef = useRef(skor);
  const giliranId = players[giliranIdx % players.length]?.id;
  const giliranKamu = giliranId === myId;
  useEffect(() => {
    matchedRef.current = matched;
  }, [matched]);
  useEffect(() => {
    skorRef.current = skor;
  }, [skor]);
  useEffect(() => {
    if (!previewAwal) return;
    const t = setTimeout(() => setPreviewAwal(false), 3000);
    return () => clearTimeout(t);
  }, []);

  // Reshuffle level 3 dipicu dari giliranIdx (naik lewat event "flip" gagal
  // yang di-broadcast, jadi nilainya SAMA di semua klien pada saat yang
  // sama) dan diacak pakai RNG seeded (bukan Math.random()) supaya urutan
  // hasil acak ulang tetap identik di semua device tanpa koordinasi server.
  useEffect(() => {
    if (!cfg.reshuffle || giliranIdx === 0 || giliranIdx % 3 !== 0) return;
    setCards(cs => acakUlangBelumMatched(cs, matchedRef.current, sessionCode + ":reshuffle:" + giliranIdx));
    setPreviewAwal(true);
    const t = setTimeout(() => setPreviewAwal(false), 3000);
    return () => clearTimeout(t);
  }, [giliranIdx]);
  const {
    sendMove,
    sendFinished,
    keluarDariSesi,
    pemainKeluar,
    pemainDitandaiKeluar,
    sesiDiakhiriKarenaKeluar,
    putuskanKelanjutan,
    reaksiMasuk,
    kirimReaksi
  } = useOnlineGame(sessionCode, d => {
    if (d.user_id === myId) return;
    const p = d.payload || {};
    if (p.type === "flip") {
      const [a, b] = [p.card_a, p.card_b];
      if (a === undefined || b === undefined) return;
      checkRef.current = true;
      setTerbuka([a, b]);
      setTimeout(() => {
        if (cards[a]?.pair === cards[b]?.pair) {
          if (d.scores) setSkor(d.scores);
          setMatched(m => {
            const nm = [...m, a, b];
            matchedRef.current = nm;
            if (nm.length === cards.length) setSelesai(true);
            return nm;
          });
          setTerbuka([]);
          checkRef.current = false;
        } else {
          setTimeout(() => {
            setTerbuka([]);
            setGiliranIdx(i => i + 1);
            checkRef.current = false;
          }, 700);
        }
      }, 900);
    }
  }, d => {
    setSkor(Object.fromEntries(d.players.map(p => [p.user_id, p.score])));
    setSelesai(true);
  });
  useEffect(() => {
    if (sesiDiakhiriKarenaKeluar) onExit();
  }, [sesiDiakhiriKarenaKeluar]);
  const klik = i => {
    if (previewAwal || !giliranKamu || checkRef.current || terbuka.includes(i) || matched.includes(i) || terbuka.length >= 2) return;
    const next = [...terbuka, i];
    setTerbuka(next);
    if (next.length === 2) {
      checkRef.current = true;
      const [a, b] = next;
      if (cards[a].pair === cards[b].pair) {
        const skorBaru = (skorRef.current[myId] || 0) + 1;
        setSkor(s => ({
          ...s,
          [myId]: skorBaru
        }));
        sendMove({
          type: "flip",
          card_a: a,
          card_b: b,
          score: skorBaru
        });
        setMatched(m => {
          const nm = [...m, a, b];
          matchedRef.current = nm;
          if (nm.length === cards.length) setSelesai(true);
          return nm;
        });
        setTerbuka([]);
        checkRef.current = false;
      } else {
        sendMove({
          type: "flip",
          card_a: a,
          card_b: b
        });
        setTimeout(() => {
          setTerbuka([]);
          setGiliranIdx(i => i + 1);
          checkRef.current = false;
        }, 900);
      }
    }
  };
  useEffect(() => {
    if (selesai) sendFinished(skorRef.current[myId] || 0);
  }, [selesai]);
  if (selesai) return /*#__PURE__*/React.createElement(HasilN, {
    players: players,
    scores: skor,
    myId: myId,
    accent: P.orange,
    onExit: onExit
  });
  const giliranNama = players.find(p => p.id === giliranId)?.nama || "?";
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "18px 20px 28px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, pemainKeluar && /*#__PURE__*/React.createElement(DialogPemainKeluar, {
    nama: pemainKeluar.nama,
    onLanjut: () => putuskanKelanjutan("continue"),
    onAkhiri: () => putuskanKelanjutan("end")
  }), /*#__PURE__*/React.createElement(EmojiReactionBar, {
    onKirim: kirimReaksi
  }), /*#__PURE__*/React.createElement(ReactionOverlay, {
    reaksi: reaksiMasuk,
    players: players
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
      marginBottom: 10
    }
  }, /*#__PURE__*/React.createElement("button", {
    onClick: () => {
      keluarDariSesi();
      onExit();
    },
    className: "gf-btn",
    style: gBtn
  }, "← Keluar"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 12,
      fontWeight: 800,
      color: giliranKamu ? P.orange : P.p2
    }
  }, previewAwal ? "Hafalkan posisi kartu… 👀" : giliranKamu ? "Giliranmu — buka 2 kartu" : `Giliran ${giliranNama}…`)), /*#__PURE__*/React.createElement(PapanSkorN, {
    players: players,
    scores: skor,
    myId: myId,
    pemainKeluarId: pemainDitandaiKeluar
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: `repeat(${cfg.kolom},1fr)`,
      gap: cfg.kolom > 4 ? 5 : 8,
      marginTop: 12
    }
  }, cards.map((c, i) => /*#__PURE__*/React.createElement(KartuView, {
    key: c.id,
    kartu: c,
    kecil: cfg.kolom > 4,
    terbuka: previewAwal || terbuka.includes(i),
    matched: matched.includes(i),
    onClick: () => klik(i),
    disabled: previewAwal || !giliranKamu || terbuka.length === 2 && !terbuka.includes(i)
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 10,
      textAlign: "center",
      fontSize: 13,
      fontWeight: 600,
      color: P.muted
    }
  }, matched.length / 2, "/", cfg.pasang, " pasang ditemukan"));
}

/* ════════════════════════════════════════════════════════════
   GAME HUB + ROUTER
════════════════════════════════════════════════════════════ */
function GameCard({
  g,
  onClick
}) {
  const [h, setH] = useState(false);
  return /*#__PURE__*/React.createElement("button", {
    onClick: onClick,
    onMouseEnter: () => setH(true),
    onMouseLeave: () => setH(false),
    className: "gf-btn gf-rise",
    style: {
      display: "flex",
      alignItems: "center",
      gap: 14,
      padding: 18,
      borderRadius: 20,
      border: `1px solid ${h ? g.warna : "rgba(255,255,255,0.1)"}`,
      background: h ? `${g.warna}08` : "rgba(255,255,255,0.03)",
      cursor: "pointer",
      color: P.cream,
      textAlign: "left",
      width: "100%"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 52,
      height: 52,
      borderRadius: 16,
      background: `${g.warna}22`,
      display: "grid",
      placeItems: "center",
      fontSize: 26,
      flexShrink: 0
    }
  }, g.ikon), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 18,
      color: P.cream
    }
  }, g.judul), /*#__PURE__*/React.createElement("div", {
    style: {
      color: P.muted,
      fontSize: 13,
      fontWeight: 600,
      marginTop: 2
    }
  }, g.desc)), /*#__PURE__*/React.createElement("span", {
    style: {
      color: g.warna,
      fontSize: 20
    }
  }, "›"));
}
function Hub({
  onGame,
  onLeaderboard
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "26px 20px 32px",
      maxWidth: 460,
      margin: "0 auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      marginBottom: 28
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 46,
      height: 46,
      borderRadius: 16,
      background: `linear-gradient(135deg,${P.gold},#E8902F)`,
      display: "grid",
      placeItems: "center",
      boxShadow: `0 8px 24px rgba(245,196,81,0.35)`,
      flexShrink: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: 22
    }
  }, "🎮")), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "'Bricolage Grotesque',sans-serif",
      fontWeight: 800,
      fontSize: 22,
      color: P.cream,
      letterSpacing: -0.4
    }
  }, "Game Rohani"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 13,
      color: P.muted,
      fontWeight: 600
    }
  }, "4 mini-game · Asah firman"))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gap: 12
    }
  }, GAME_DEFS.map((g, i) => /*#__PURE__*/React.createElement(GameCard, {
    key: g.id,
    g: g,
    onClick: () => onGame(g)
  }))), /*#__PURE__*/React.createElement("button", {
    onClick: onLeaderboard,
    className: "gf-btn",
    style: {
      width: "100%",
      marginTop: 18,
      padding: "14px",
      borderRadius: 18,
      border: `1px solid ${P.gold}44`,
      background: `${P.gold}0d`,
      color: P.gold,
      fontWeight: 800,
      fontSize: 15,
      cursor: "pointer",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("span", null, "🏆"), " Papan Peringkat Mingguan"));
}
function GameFeature() {
  const [screen, setScreen] = useState("hub");
  const [game, setGame] = useState(null);
  const [mode, setMode] = useState(null);
  const [lawan, setLawan] = useState(null); // 1 lawan (mode "tatap")
  const [lawanList, setLawanList] = useState([]); // 1-3 lawan (mode "online")
  const [sessionCode, setSessionCode] = useState(null);
  const [players, setPlayers] = useState(null); // semua peserta 'accepted' termasuk diri sendiri, diisi begitu sesi online aktif
  const [hindariKeys, setHindariKeys] = useState([]); // soal yang harus dihindari (baru dipakai tenant ini beberapa match terakhir)
  const [hostId, setHostId] = useState(null); // hanya host yang mencatat soal terpakai ke server (lihat GameSessionController::recordQuestions)
  const [notif, setNotif] = useState(null);
  const [notifHostName, setNotifHostName] = useState(null); // disalin dari notif saat diterima — dipakai LobiOnline invitee, karena notif sendiri langsung di-null-kan
  const [memoryLevel, setMemoryLevel] = useState(1); // 1=4x4, 2=8x8, 3=8x8+reshuffle — dipakai Solo/Tatap/Online

  const pulang = () => {
    setScreen("hub");
    setGame(null);
    setMode(null);
    setLawan(null);
    setLawanList([]);
    setSessionCode(null);
    setPlayers(null);
    setHindariKeys([]);
    setHostId(null);
    setMemoryLevel(1);
    setNotifHostName(null);
  };
  const mulaiGame = g => {
    setGame(g);
    setScreen("cara");
  };
  const pilihCara = m => {
    setMode(m);
    if (game?.id === "memory") {
      setScreen("level");
      return;
    } // Memory Match: pilih level dulu di semua mode
    if (m === "solo") setScreen("main");else setScreen("lawan");
  };
  const pilihLevel = lvl => {
    setMemoryLevel(lvl);
    if (mode === "solo") setScreen("main");else setScreen("lawan");
  };
  const pilihLawan = picked => {
    if (mode === "online") {
      setLawanList(Array.isArray(picked) ? picked : [picked]);
      setScreen("lobi");
    } else {
      setLawan(picked);
      setScreen("main");
    }
  };

  // Begitu sessionCode online tersedia (baik dari lobi maupun terima notif),
  // ambil daftar lengkap pemain dari server — tidak cukup mengandalkan data
  // notif/lobi lokal karena bisa ada peserta lain yang tidak kita tahu (mis.
  // kita opponent, ada opponent lain juga yang tidak muncul di notif kita).
  useEffect(() => {
    if (mode !== "online" || !sessionCode || screen !== "main") return;
    let batal = false;
    apiGet("/game/session/" + sessionCode).then(s => {
      if (batal) return;
      const aktif = (s.players || []).filter(p => p.status === "accepted");
      setPlayers(aktif.map(p => ({
        id: p.id,
        nama: p.nama
      })));
      setHindariKeys(s.seed?.avoid_keys || []);
      setHostId(s.host?.id ?? null);
      if (s.seed?.level) setMemoryLevel(s.seed.level);
    }).catch(() => {});
    return () => {
      batal = true;
    };
  }, [mode, sessionCode, screen]);

  // Langganan notifikasi tantangan masuk via Pusher
  useEffect(() => {
    const userId = window.__GAME_USER__?.id;
    if (!userId) return;
    const pusher = getPusher();
    if (!pusher) return;
    const ch = pusher.subscribe("private-game-user." + userId);
    ch.bind("challenged", d => {
      setNotif(d);
    });
    return () => {
      pusher.unsubscribe("private-game-user." + userId);
    };
  }, []);

  // Dipakai baik dari kartu notifikasi in-page (Pusher, lihat effect di
  // bawah) MAUPUN dari notifikasi global di layout luar /game (lonceng di
  // topbar semua halaman) yang membawa pengguna kembali ke /game?join=KODE
  // — lihat effect "auto-join dari query ?join=" di bawah.
  const terimaSesi = async (sessionCode, gameType, hostName) => {
    try {
      await apiPost("/game/respond", {
        session_code: sessionCode,
        accept: true
      });
      setGame(GAME_DEFS.find(g => g.id === gameType) || {
        id: gameType,
        warna: P.gold
      });
      setSessionCode(sessionCode);
      setNotifHostName(hostName);
      setMode("online");
      // JANGAN langsung ke "main" — host mungkin belum menekan "Mulai" (masih
      // menunggu peserta lain merespons). Arahkan ke lobi tunggu yang sama
      // dengan sisi host, tapi tanpa tombol "Mulai" (hanya host yang boleh
      // memicu /game/start) — supaya kedua sisi baru masuk game bersamaan
      // begitu host benar-benar menekan Mulai.
      setLawanList([]);
      setScreen("lobi-invitee");
    } catch (e) {/* sesi mungkin sudah kedaluwarsa/dibatalkan — biarkan user di hub */}
  };
  const terimaNotif = async () => {
    if (!notif) return;
    await terimaSesi(notif.session_code, notif.game_type, notif.host_name);
    setNotif(null);
  };
  const tolakNotif = async () => {
    if (!notif) return;
    try {
      await apiPost("/game/respond", {
        session_code: notif.session_code,
        accept: false
      });
    } catch (e) {}
    setNotif(null);
  };

  // Auto-join saat dibuka dari notifikasi tantangan yang tampil di HALAMAN
  // LAIN (luar /game) — lihat window.__icareGameChallengeToast di
  // layouts/app.blade.php. Tombol "Terima" di sana membawa user ke
  // /game?join=KODE alih-alih menerima langsung di tempat, supaya
  // /game/respond tetap dipanggil dari dalam komponen game yang sudah
  // pasti berhasil dimuat (dan bukan duplikat logic Pusher di luar React).
  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const kodeJoin = params.get("join");
    if (!kodeJoin) return;
    window.history.replaceState({}, "", "/game"); // bersihkan query supaya tidak ke-trigger lagi kalau reload
    apiGet("/game/pending").then(p => {
      if (p.pending && p.session_code === kodeJoin) {
        terimaSesi(p.session_code, p.game_type, p.host_name);
      }
    }).catch(() => {});
  }, []);
  const GameMain = () => {
    if (!game) return null;
    if (mode === "solo") {
      if (game.id === "kuis") return /*#__PURE__*/React.createElement(KuisSolo, {
        onExit: pulang
      });
      if (game.id === "susun") return /*#__PURE__*/React.createElement(SusunSolo, {
        onExit: pulang
      });
      if (game.id === "tebak") return /*#__PURE__*/React.createElement(TebakSolo, {
        onExit: pulang
      });
      if (game.id === "memory") return /*#__PURE__*/React.createElement(MemorySolo, {
        level: memoryLevel,
        onExit: pulang
      });
    }
    if (mode === "tatap") {
      if (game.id === "kuis") return /*#__PURE__*/React.createElement(KuisTatap, {
        lawan: lawan,
        onExit: pulang
      });
      if (game.id === "susun") return /*#__PURE__*/React.createElement(SusunTatap, {
        lawan: lawan,
        onExit: pulang
      });
      if (game.id === "tebak") return /*#__PURE__*/React.createElement(TebakTatap, {
        lawan: lawan,
        onExit: pulang
      });
      if (game.id === "memory") return /*#__PURE__*/React.createElement(MemoryTatap, {
        lawan: lawan,
        level: memoryLevel,
        onExit: pulang
      });
    }
    if (mode === "online") {
      if (!players) return /*#__PURE__*/React.createElement("div", {
        style: {
          padding: 60,
          textAlign: "center",
          color: P.muted,
          fontWeight: 600
        }
      }, "Memuat pemain…");
      const gType = game.id;
      if (gType === "kuis") return /*#__PURE__*/React.createElement(KuisOnline, {
        players: players,
        sessionCode: sessionCode,
        hindariKeys: hindariKeys,
        hostId: hostId,
        onExit: pulang
      });
      if (gType === "susun") return /*#__PURE__*/React.createElement(SusunOnline, {
        players: players,
        sessionCode: sessionCode,
        hindariKeys: hindariKeys,
        hostId: hostId,
        onExit: pulang
      });
      if (gType === "tebak") return /*#__PURE__*/React.createElement(TebakOnline, {
        players: players,
        sessionCode: sessionCode,
        hindariKeys: hindariKeys,
        hostId: hostId,
        onExit: pulang
      });
      if (gType === "memory") return /*#__PURE__*/React.createElement(MemoryOnline, {
        players: players,
        sessionCode: sessionCode,
        hindariKeys: hindariKeys,
        hostId: hostId,
        level: memoryLevel,
        onExit: pulang
      });
    }
    return null;
  };
  return /*#__PURE__*/React.createElement("div", {
    style: {
      minHeight: 560,
      width: "100%",
      background: `radial-gradient(120% 90% at 50% -10%,${P.nightSoft} 0%,${P.night} 55%,#120D2E 100%)`,
      color: P.cream,
      fontFamily: "'Plus Jakarta Sans',sans-serif",
      borderRadius: 24,
      overflow: "hidden",
      position: "relative"
    }
  }, /*#__PURE__*/React.createElement(GStyles, null), screen === "hub" && /*#__PURE__*/React.createElement(Hub, {
    onGame: mulaiGame,
    onLeaderboard: () => setScreen("leaderboard")
  }), screen === "leaderboard" && /*#__PURE__*/React.createElement(PapanPeringkat, {
    onBack: pulang
  }), screen === "cara" && game && /*#__PURE__*/React.createElement(PilihCara, {
    game: game,
    onBack: pulang,
    onPick: pilihCara
  }), screen === "level" && game && /*#__PURE__*/React.createElement(PilihLevelMemory, {
    onBack: () => setScreen("cara"),
    onPick: pilihLevel
  }), screen === "lawan" && game && /*#__PURE__*/React.createElement(PilihLawan, {
    game: game,
    mode: mode,
    onBack: () => setScreen(game.id === "memory" ? "level" : "cara"),
    onPick: pilihLawan
  }), screen === "lobi" && game && lawanList.length > 0 && /*#__PURE__*/React.createElement(LobiOnline, {
    lawanList: lawanList,
    game: game,
    level: memoryLevel,
    onBack: () => setScreen("lawan"),
    onMulai: code => {
      setSessionCode(code);
      setScreen("main");
    },
    onDeclined: () => setScreen("lawan")
  }), screen === "lobi-invitee" && game && sessionCode && /*#__PURE__*/React.createElement(LobiOnline, {
    lawanList: [],
    game: game,
    sessionCode: sessionCode,
    isHost: false,
    hostName: notifHostName,
    onBack: pulang,
    onMulai: code => {
      setSessionCode(code);
      setScreen("main");
    },
    onDeclined: pulang
  }), screen === "main" && /*#__PURE__*/React.createElement(GameMain, null), notif && screen !== "main" && /*#__PURE__*/React.createElement(NotifTantangan, {
    notif: notif,
    onTerima: terimaNotif,
    onTolak: tolakNotif
  }));
}

/* ── MOUNT ──────────────────────────────────────────────────── */
const rootEl = document.getElementById("game-root");
if (rootEl) ReactDOM.createRoot(rootEl).render(/*#__PURE__*/React.createElement(GameFeature, null));