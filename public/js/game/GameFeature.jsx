/* ================================================================
   GAME FEATURE — Hub 4 Mini-Game Rohani
   Requires: React 18 CDN + Babel Standalone (no import/export)
================================================================ */
const { useState, useEffect, useRef, useCallback } = React;

/* ── PALETTE ───────────────────────────────────────────────── */
const P = { night:"#1A1340",nightSoft:"#241A57",gold:"#F5C451",goldSoft:"#FFE08A",cream:"#F7F3E8",green:"#4ADE80",red:"#FF6B6B",p1:"#F5C451",p2:"#5EE0D0",muted:"#A99FD6",purple:"#A78BFA",orange:"#FF9F8E" };
const AVC = ["#F5C451","#5EE0D0","#FF9F8E","#A78BFA","#7DD3FC","#FCA5D8","#86EFAC","#FDBA74"];
const gBtn = { padding:"8px 14px",borderRadius:12,border:"1px solid rgba(255,255,255,0.14)",background:"rgba(255,255,255,0.04)",color:"#F7F3E8",fontWeight:700,fontSize:13,cursor:"pointer",fontFamily:"inherit" };

/* ── BANK DATA ─────────────────────────────────────────────── */
const BANK_SOAL = [
  {q:"Siapa yang membangun bahtera atas perintah Tuhan?",opsi:["Musa","Nuh","Abraham","Yusuf"],benar:1},
  {q:"Di kota manakah Yesus dilahirkan?",opsi:["Nazaret","Yerusalem","Betlehem","Kapernaum"],benar:2},
  {q:"Berapa jumlah murid Yesus?",opsi:["10","12","7","40"],benar:1},
  {q:"Siapa yang menerima Sepuluh Perintah Allah di Gunung Sinai?",opsi:["Harun","Yosua","Musa","Daud"],benar:2},
  {q:"Kitab pertama dalam Alkitab adalah?",opsi:["Keluaran","Kejadian","Mazmur","Yohanes"],benar:1},
  {q:"Siapa yang mengalahkan raksasa Goliat?",opsi:["Saul","Simson","Daud","Yonatan"],benar:2},
  {q:"Pada hari ke berapa Tuhan beristirahat setelah menciptakan dunia?",opsi:["Keenam","Ketujuh","Kelima","Kedelapan"],benar:1},
  {q:"Siapa nabi yang ditelan ikan besar?",opsi:["Yesaya","Yunus","Elia","Daniel"],benar:1},
  {q:"Siapa ibu Yesus?",opsi:["Marta","Maria","Elisabet","Hana"],benar:1},
  {q:"Mukjizat pertama Yesus mengubah air menjadi?",opsi:["Roti","Madu","Anggur","Minyak"],benar:2},
  {q:"Siapa yang menyangkal Yesus tiga kali?",opsi:["Yudas","Tomas","Yohanes","Petrus"],benar:3},
  {q:"Taman tempat Adam dan Hawa tinggal disebut?",opsi:["Getsemani","Eden","Sinai","Galilea"],benar:1},
  {q:"Siapa yang membaptis Yesus di Sungai Yordan?",opsi:["Petrus","Andreas","Yohanes Pembaptis","Elia"],benar:2},
  {q:"Berapa lama bangsa Israel mengembara di padang gurun?",opsi:["7 tahun","40 tahun","12 tahun","70 tahun"],benar:1},
  {q:"Siapa yang menjual Yusuf kepada para pedagang?",opsi:["Ayahnya","Orang Mesir","Saudara-saudaranya","Firaun"],benar:2},
  {q:"Kitab terakhir dalam Alkitab adalah?",opsi:["Maleakhi","Wahyu","Yudas","Kisah Para Rasul"],benar:1},
  {q:"Raja Israel yang terkenal bijaksana dan membangun Bait Suci pertama?",opsi:["Daud","Saul","Salomo","Hizkia"],benar:2},
  {q:"Roh Kudus turun atas para murid pada hari?",opsi:["Paskah","Pentakosta","Natal","Sabat"],benar:1},
  {q:"Siapa yang berjalan di atas air lalu mulai tenggelam?",opsi:["Yohanes","Yakobus","Petrus","Andreas"],benar:2},
  {q:"Berapa banyak kitab dalam Perjanjian Baru?",opsi:["39","27","66","12"],benar:1},
  {q:"Ratu yang menyelamatkan bangsanya, namanya menjadi judul kitab?",opsi:["Rut","Debora","Ester","Hana"],benar:2},
  {q:"Siapa yang dibangkitkan Yesus dari kematian setelah empat hari?",opsi:["Lazarus","Nikodemus","Bartimeus","Yairus"],benar:0},
  {q:"Makanan apa yang Tuhan turunkan dari langit di padang gurun?",opsi:["Roti","Buah ara","Manna","Madu"],benar:2},
  {q:"Rasul yang dulu menganiaya orang Kristen lalu menulis banyak surat?",opsi:["Petrus","Paulus","Barnabas","Lukas"],benar:1},
  {q:"Sungai tempat bayi Musa dihanyutkan?",opsi:["Yordan","Nil","Efrat","Tigris"],benar:1},
  {q:"Siapa istri pertama yang diciptakan Tuhan?",opsi:["Sara","Hawa","Rebeka","Rahel"],benar:1},
  {q:"Siapa yang diikat Abraham untuk dipersembahkan di Gunung Moria?",opsi:["Ismael","Esau","Ishak","Yakub"],benar:2},
  {q:"Berapa hari Yesus berpuasa di padang gurun?",opsi:["7 hari","40 hari","30 hari","3 hari"],benar:1},
  {q:"Siapa yang memimpin bangsa Israel masuk ke Tanah Kanaan setelah Musa wafat?",opsi:["Kaleb","Yosua","Gideon","Simson"],benar:1},
  {q:"Nabi mana yang naik ke surga dengan kereta berapi?",opsi:["Elisa","Elia","Yesaya","Yeremia"],benar:1},
  {q:"Siapa yang kekuatannya terletak pada rambutnya?",opsi:["Gideon","Simson","Boas","Otniel"],benar:1},
  {q:"Berapa jumlah tembok Yerikho runtuh setelah dikelilingi bangsa Israel?",opsi:["Ke-3","Ke-5","Ke-7","Ke-12"],benar:2},
  {q:"Siapa yang menafsirkan mimpi Firaun tentang tujuh tahun kelimpahan dan kelaparan?",opsi:["Musa","Yusuf","Daniel","Yakub"],benar:1},
  {q:"Kota apa yang dihancurkan Tuhan bersama Gomora karena dosa besar?",opsi:["Sodom","Niniwe","Babel","Tirus"],benar:0},
  {q:"Siapa nama istri Lot yang menjadi tiang garam?",opsi:["Tidak disebutkan namanya","Sara","Milka","Naomi"],benar:0},
  {q:"Siapa hakim perempuan yang memimpin Israel?",opsi:["Ester","Debora","Rut","Hulda"],benar:1},
  {q:"Berapa keping perak Yudas menerima untuk mengkhianati Yesus?",opsi:["10","20","30","50"],benar:2},
  {q:"Di bukit apa Yesus disalibkan?",opsi:["Sinai","Golgota","Sion","Karmel"],benar:1},
  {q:"Siapa murid yang meragukan kebangkitan Yesus sampai melihat bekas lukanya?",opsi:["Tomas","Filipus","Bartolomeus","Matius"],benar:0},
  {q:"Siapa yang menulis sebagian besar kitab Mazmur?",opsi:["Salomo","Musa","Daud","Asaf"],benar:2},
  {q:"Nabi mana yang menikahi seorang perempuan sundal atas perintah Tuhan sebagai lambang?",opsi:["Hosea","Amos","Mikha","Zefanya"],benar:0},
  {q:"Siapa raja yang memerintahkan pembunuhan bayi-bayi di Betlehem?",opsi:["Herodes","Pilatus","Kaisar Agustus","Ahab"],benar:0},
  {q:"Gunung apa tempat Musa melihat Tanah Perjanjian sebelum wafat?",opsi:["Sinai","Horeb","Nebo","Karmel"],benar:2},
  {q:"Siapa yang menjadi menantu Musa dan memberi nasihat sistem pengadilan?",opsi:["Yitro","Harun","Kaleb","Hur"],benar:0},
  {q:"Kitab apa yang berisi surat cinta/puisi kasih antara mempelai?",opsi:["Amsal","Pengkhotbah","Kidung Agung","Ratapan"],benar:2},
  {q:"Siapa nabi yang menantang 450 nabi Baal di Gunung Karmel?",opsi:["Elisa","Elia","Yesaya","Yehezkiel"],benar:1},
  {q:"Berapa lama Yunus berada dalam perut ikan?",opsi:["1 hari 1 malam","3 hari 3 malam","7 hari","40 hari"],benar:1},
  {q:"Siapa yang menjadi raja pertama bangsa Israel?",opsi:["Daud","Saul","Salomo","Ish-Boset"],benar:1},
  {q:"Rasul mana yang dijuluki 'kekasih Yesus' dan menulis Injil keempat?",opsi:["Matius","Markus","Lukas","Yohanes"],benar:3},
  {q:"Di manakah Paulus bertobat setelah melihat cahaya dari langit?",opsi:["Yerusalem","Damaskus","Antiokhia","Roma"],benar:1},
];

const BANK_AYAT = [
  {ref:"Yohanes 3:16",  teks:"Karena begitu besar kasih Allah akan dunia ini sehingga Ia telah mengaruniakan Anak-Nya yang tunggal"},
  {ref:"Mazmur 23:1",   teks:"Tuhan adalah gembalaku takkan kekurangan aku"},
  {ref:"Filipi 4:13",   teks:"Segala perkara dapat kutanggung di dalam Dia yang memberi kekuatan kepadaku"},
  {ref:"Yosua 1:9",     teks:"Kuatkan dan teguhkanlah hatimu sebab Tuhan Allahmu menyertai engkau"},
  {ref:"Amsal 3:5",     teks:"Percayalah kepada Tuhan dengan segenap hatimu dan janganlah bersandar kepada pengertianmu sendiri"},
  {ref:"Roma 8:28",     teks:"Kita tahu sekarang bahwa Allah turut bekerja dalam segala sesuatu untuk mendatangkan kebaikan"},
  {ref:"Mazmur 46:1",   teks:"Allah itu bagi kita tempat perlindungan dan kekuatan sebagai penolong dalam kesesakan"},
  {ref:"Yesaya 40:31",  teks:"Orang yang menanti Tuhan mendapat kekuatan baru mereka seperti rajawali yang naik terbang"},
  {ref:"1 Yohanes 4:8", teks:"Barangsiapa tidak mengasihi ia tidak mengenal Allah sebab Allah adalah kasih"},
  {ref:"Matius 5:9",    teks:"Berbahagialah orang yang membawa damai karena mereka akan disebut anak-anak Allah"},
  {ref:"Mazmur 121:2",  teks:"Pertolonganku ialah dari Tuhan yang menjadikan langit dan bumi"},
  {ref:"Yeremia 29:11", teks:"Sebab Aku mengetahui rancangan yang ada pada-Ku yaitu rancangan damai sejahtera bukan kecelakaan"},
  {ref:"Amsal 16:3",    teks:"Serahkanlah perbuatanmu kepada Tuhan maka terlaksanalah segala rencanamu"},
  {ref:"Mazmur 27:1",   teks:"Tuhan adalah terang dan keselamatanku kepada siapakah aku harus takut"},
  {ref:"Matius 6:33",   teks:"Carilah dahulu kerajaan Allah dan kebenarannya maka semuanya akan ditambahkan kepadamu"},
  {ref:"Roma 12:2",     teks:"Janganlah kamu menjadi serupa dengan dunia ini tetapi berubahlah oleh pembaruan budimu"},
  {ref:"Galatia 5:22",  teks:"Buah roh ialah kasih sukacita damai sejahtera kesabaran kemurahan kebaikan kesetiaan"},
  {ref:"Mazmur 34:8",   teks:"Kecaplah dan lihatlah betapa baiknya Tuhan berbahagialah orang yang berlindung pada-Nya"},
  {ref:"Ibrani 11:1",   teks:"Iman adalah dasar dari segala sesuatu yang kita harapkan bukti dari segala sesuatu yang tidak kita lihat"},
  {ref:"1 Korintus 13:4",teks:"Kasih itu sabar kasih itu murah hati ia tidak cemburu ia tidak memegahkan diri"},
  {ref:"Mazmur 119:105",teks:"Firman-Mu itu pelita bagi kakiku dan terang bagi jalanku"},
  {ref:"Efesus 2:8",    teks:"Karena kasih karunia kamu diselamatkan oleh iman itu bukan hasil usahamu tetapi pemberian Allah"},
  {ref:"Filipi 4:6",    teks:"Janganlah hendaknya kamu kuatir tentang apapun juga tetapi nyatakanlah dalam segala hal keinginanmu kepada Allah"},
  {ref:"Mazmur 37:4",   teks:"Bergembiralah karena Tuhan maka Ia akan memberikan kepadamu apa yang diinginkan hatimu"},
  {ref:"2 Timotius 1:7",teks:"Allah memberikan kepada kita roh yang tidak menimbulkan ketakutan tetapi roh yang membangkitkan kekuatan"},
  {ref:"Mazmur 1:1",    teks:"Berbahagialah orang yang tidak berjalan menurut nasihat orang fasik"},
  {ref:"Amsal 22:6",    teks:"Didiklah orang muda menurut jalan yang patut baginya maka pada masa tuanya ia tidak akan menyimpang"},
  {ref:"Yakobus 1:5",   teks:"Apabila di antara kamu ada yang kekurangan hikmat hendaklah ia memintanya kepada Allah"},
  {ref:"Kolose 3:23",   teks:"Apapun juga yang kamu perbuat perbuatlah dengan segenap hatimu seperti untuk Tuhan"},
  {ref:"Mazmur 91:1",   teks:"Orang yang duduk dalam lindungan Yang Mahatinggi akan bermalam dalam naungan Yang Mahakuasa"},
];

const BANK_TOKOH = [
  {jawaban:"Musa",   clues:["Dibesarkan di istana Mesir","Memimpin bangsa Israel keluar dari perbudakan","Menerima Sepuluh Perintah Allah di Gunung Sinai"],   salah:["Abraham","Elias","Harun"]},
  {jawaban:"Daud",   clues:["Mulanya seorang gembala domba","Mengalahkan raksasa dengan batu dan umban","Menulis banyak Mazmur dan menjadi raja Israel"],        salah:["Goliat","Yonatan","Saul"]},
  {jawaban:"Yusuf",  clues:["Anak kesayangan Yakub dengan jubah istimewa","Dijual oleh saudara-saudaranya ke Mesir","Menjadi perdana menteri Mesir setelah menafsirkan mimpi Firaun"], salah:["Yakub","Benyamin","Ruben"]},
  {jawaban:"Daniel", clues:["Dibuang ke Babel semasa muda","Dimasukkan ke gua singa karena setia berdoa","Menafsirkan mimpi dan tulisan di dinding istana raja"],salah:["Sadrakh","Mesakh","Abednego"]},
  {jawaban:"Ester",  clues:["Seorang Yahudi yang menjadi ratu Persia","Menolong bangsanya dari rencana jahat Haman","Namanya diabadikan dalam salah satu kitab Alkitab"],salah:["Rut","Debora","Hana"]},
  {jawaban:"Rut",    clues:["Wanita Moab yang setia menemani mertuanya Naomi","Memungut jelai di ladang Boas","Menjadi nenek moyang Daud dan Yesus"],            salah:["Orpa","Naomi","Ester"]},
  {jawaban:"Petrus", clues:["Seorang nelayan di Danau Galilea","Pernah berjalan di atas air menemui Yesus","Menyangkal Yesus tiga kali sebelum fajar"],          salah:["Yohanes","Andreas","Yakobus"]},
  {jawaban:"Paulus", clues:["Pernah menganiaya orang Kristen dengan giat","Bertobat setelah melihat cahaya di jalan Damaskus","Menulis lebih dari separuh surat dalam Perjanjian Baru"],salah:["Barnabas","Silas","Lukas"]},
  {jawaban:"Nuh",    clues:["Hidup 950 tahun lamanya","Membangun bahtera raksasa atas perintah Tuhan","Menyelamatkan keluarganya dan hewan dari air bah"],      salah:["Abraham","Lot","Sem"]},
  {jawaban:"Yunus",  clues:["Melarikan diri ke Tarsis menghindari tugas Tuhan","Ditelan seekor ikan besar selama tiga hari tiga malam","Memberitakan pertobatan kepada kota Niniwe"],salah:["Elia","Yesaya","Mikha"]},
  {jawaban:"Abraham",clues:["Dipanggil Tuhan meninggalkan tanah kelahirannya","Disebut bapak segala bangsa yang percaya","Bersedia mempersembahkan anaknya Ishak di Gunung Moria"],salah:["Ishak","Yakub","Lot"]},
  {jawaban:"Simson",clues:["Kekuatannya terletak pada rambutnya yang panjang","Jatuh cinta pada Delila yang mengkhianatinya","Merobohkan kuil orang Filistin dengan kekuatan terakhirnya"],salah:["Gideon","Boas","Otniel"]},
  {jawaban:"Salomo",clues:["Anak Daud yang terkenal karena hikmatnya","Membangun Bait Suci pertama di Yerusalem","Menulis kitab Amsal dan Kidung Agung"],salah:["Rehabeam","Daud","Hizkia"]},
  {jawaban:"Elia",   clues:["Menantang para nabi Baal di Gunung Karmel","Diberi makan burung gagak di tepi sungai Kerit","Naik ke surga dengan kereta dan kuda berapi"],salah:["Elisa","Yesaya","Yeremia"]},
  {jawaban:"Yakub",  clues:["Adik kembar Esau yang licik merebut berkat sulung","Bermimpi tentang tangga yang sampai ke langit","Namanya diubah menjadi Israel setelah bergumul dengan malaikat"],salah:["Esau","Ishak","Laban"]},
  {jawaban:"Yohanes Pembaptis",clues:["Anak Zakharia dan Elisabet di masa tuanya","Hidup di padang gurun memakan belalang dan madu hutan","Membaptis Yesus di Sungai Yordan"],salah:["Yohanes Rasul","Elia","Yakobus"]},
  {jawaban:"Maria",  clues:["Seorang perawan dari Nazaret","Menerima kabar dari malaikat Gabriel bahwa ia akan mengandung","Menjadi ibu dari Yesus Kristus"],salah:["Marta","Elisabet","Maria Magdalena"]},
  {jawaban:"Yudas Iskariot",clues:["Salah satu dari kedua belas murid Yesus","Memegang kas kelompok murid","Mengkhianati Yesus dengan sebuah ciuman demi tiga puluh keping perak"],salah:["Tomas","Simon Zelot","Matius"]},
  {jawaban:"Gideon", clues:["Awalnya takut dan meminta tanda bulu domba dari Tuhan","Memimpin hanya 300 orang melawan tentara Midian","Menjadi salah satu hakim Israel"],salah:["Simson","Barak","Yefta"]},
  {jawaban:"Zakheus",clues:["Seorang kepala pemungut cukai yang kaya","Bertubuh pendek sehingga memanjat pohon ara","Bertobat dan mengembalikan hartanya empat kali lipat setelah bertemu Yesus"],salah:["Matius","Simon","Bartimeus"]},
];

const BANK_KARTU = [
  {a:"Nuh",    b:"Membangun bahtera dari kayu gofir"},
  {a:"Musa",   b:"Membelah Laut Merah dengan tongkat"},
  {a:"Daud",   b:"Mengalahkan Goliat dengan batu"},
  {a:"Yusuf",  b:"Jubah indah berwarna-warni"},
  {a:"Daniel", b:"Selamat dari gua singa"},
  {a:"Simson", b:"Kekuatan terletak pada rambutnya"},
  {a:"Elia",   b:"Naik ke surga dengan kereta api"},
  {a:"Yunus",  b:"Tiga hari dalam perut ikan"},
  {a:"Maria",  b:"Ibu dari Yesus Kristus"},
  {a:"Petrus", b:"Kunci kerajaan surga"},
  {a:"Paulus", b:"Bertobat di jalan Damaskus"},
  {a:"Abraham",b:"Bapak segala bangsa"},
  {a:"Salomo", b:"Membangun Bait Suci pertama"},
  {a:"Ester",  b:"Menyelamatkan bangsa Yahudi"},
  {a:"Rut",    b:"Setia mengikuti mertua Naomi"},
  {a:"Yosua",  b:"Memimpin Israel masuk Kanaan"},
  {a:"Adam",   b:"Manusia pertama yang diciptakan"},
  {a:"Hawa",   b:"Wanita pertama, tergoda ulat"},
  {a:"Ishak",  b:"Anak perjanjian Abraham dan Sara"},
  {a:"Yakub",  b:"Bergumul dengan malaikat semalaman"},
  {a:"Gideon", b:"Menang perang dengan 300 orang"},
  {a:"Debora", b:"Hakim perempuan Israel"},
  {a:"Zakheus",b:"Memanjat pohon ara demi melihat Yesus"},
  {a:"Yohanes Pembaptis",b:"Membaptis Yesus di Sungai Yordan"},
  {a:"Tomas",  b:"Meragukan kebangkitan Yesus"},
  {a:"Lazarus",b:"Dibangkitkan setelah empat hari mati"},
  {a:"Yudas Iskariot",b:"Mengkhianati Yesus demi uang"},
  {a:"Hizkia", b:"Raja yang sembuh dari sakit parah"},
  {a:"Yesaya", b:"Menubuatkan kelahiran Mesias"},
  {a:"Yeremia",b:"Dijuluki nabi yang menangis"},
];

function getLeaderboard(){const raw=window.__GAME_LEADERBOARD__;if(!raw||!raw.length)return [];return raw;}

const GAME_DEFS = [
  {id:"kuis",  ikon:"⚡",judul:"Kuis Adu Cepat",desc:"Trivia Alkitab — jawab tercepat",warna:P.gold},
  {id:"susun", ikon:"📖",judul:"Susun Ayat",     desc:"Acak kata — rangkai ayat suci",  warna:P.p2},
  {id:"tebak", ikon:"🔍",judul:"Tebak Tokoh",    desc:"Clue bertahap — siapa aku?",     warna:P.purple},
  {id:"memory",ikon:"🃏",judul:"Memory Match",   desc:"Cocokkan kartu — uji ingatan",   warna:P.orange},
];

/* ── UTILS ─────────────────────────────────────────────────── */
function shuffle(arr){const a=[...arr];for(let i=a.length-1;i>0;i--){const j=0|Math.random()*(i+1);[a[i],a[j]]=[a[j],a[i]];}return a;}

/* RNG berbasis seed (mulberry32) supaya kedua pemain di sesi online yang
   sama mendapat urutan acak IDENTIK — tanpa ini tiap klien memakai
   Math.random() sendiri-sendiri sehingga soal & jawaban berbeda. */
function buatRng(seed){
  let s=0;for(let i=0;i<seed.length;i++)s=(s*31+seed.charCodeAt(i))>>>0;
  return function(){s|=0;s=(s+0x6D2B79F5)|0;let t=Math.imul(s^s>>>15,1|s);t=(t+Math.imul(t^t>>>7,61|t))^t;return((t^t>>>14)>>>0)/4294967296;};
}
function shuffleSeed(arr,rng){const a=[...arr];for(let i=a.length-1;i>0;i--){const j=0|rng()*(i+1);[a[i],a[j]]=[a[j],a[i]];}return a;}
function siapkanSoalSeed(n,seed){const rng=buatRng(seed);const dipilih=shuffleSeed(BANK_SOAL,rng).slice(0,n);return dipilih.map(s=>{const b=s.opsi[s.benar];const o=shuffleSeed(s.opsi,rng);return{q:s.q,opsi:o,benar:o.indexOf(b)};});}
function siapkanAyatSeed(n,seed){const rng=buatRng(seed);const dipilih=shuffleSeed(BANK_AYAT,rng).slice(0,n);return dipilih.map(a=>{const kata=a.teks.split(" ");return{ref:a.ref,kata,acak:shuffleSeed([...kata],rng)};});}
function siapkanTokohSeed(n,seed){const rng=buatRng(seed);const dipilih=shuffleSeed(BANK_TOKOH,rng).slice(0,n);return dipilih.map(t=>{let op=shuffleSeed([t.jawaban,...t.salah],rng).slice(0,4);if(!op.includes(t.jawaban))op[0]=t.jawaban;return{...t,opsi:shuffleSeed(op,rng)};});}
function siapkanKartuSeed(n=8,seed){const rng=buatRng(seed);const p=shuffleSeed(BANK_KARTU,rng).slice(0,n);return shuffleSeed([...p.map((x,i)=>({id:i*2,pair:i,isi:x.a})),...p.map((x,i)=>({id:i*2+1,pair:i,isi:x.b}))],rng);}
function inisial(n){return n.split(" ").map(w=>w[0]).join("").slice(0,2).toUpperCase();}
function warnaDari(n){let h=0;for(let i=0;i<n.length;i++)h=n.charCodeAt(i)+((h<<5)-h);return AVC[Math.abs(h)%AVC.length];}

/* Anti-pengulangan: catat item yang baru dipakai (per kategori) di localStorage,
   dan pada pengambilan berikutnya utamakan item yang BELUM ada di riwayat itu
   dulu sebelum terpaksa mengulang. Riwayat dibatasi supaya begitu bank sudah
   "habis dijelajahi", item lama otomatis boleh muncul lagi (bukan diblokir permanen). */
function ambilRiwayat(key){
  try{return JSON.parse(localStorage.getItem("gf_riwayat_"+key)||"[]");}catch(e){return [];}
}
function simpanRiwayat(key,ids){
  try{localStorage.setItem("gf_riwayat_"+key,JSON.stringify(ids.slice(-200)));}catch(e){/* localStorage penuh/nonaktif, abaikan */}
}
function pilihSegar(bank,n,key,idFn){
  const riwayat=new Set(ambilRiwayat(key));
  const segar=bank.filter(x=>!riwayat.has(idFn(x)));
  const kandidat=segar.length>=n?segar:bank; // riwayat penuh -> reset, boleh ulang dari semua
  const pilihan=shuffle(kandidat).slice(0,Math.min(n,bank.length));
  const riwayatBaru=[...ambilRiwayat(key),...pilihan.map(idFn)];
  simpanRiwayat(key,riwayatBaru);
  return pilihan;
}

function siapkanSoal(n){return pilihSegar(BANK_SOAL,n,"soal",s=>s.q).map(s=>{const b=s.opsi[s.benar];const o=shuffle(s.opsi);return{q:s.q,opsi:o,benar:o.indexOf(b)};});}
function siapkanAyat(n){return pilihSegar(BANK_AYAT,n,"ayat",a=>a.ref).map(a=>{const kata=a.teks.split(" ");return{ref:a.ref,kata,acak:shuffle([...kata])};});}
function siapkanTokoh(n){return pilihSegar(BANK_TOKOH,n,"tokoh",t=>t.jawaban).map(t=>{let op=shuffle([t.jawaban,...t.salah]).slice(0,4);if(!op.includes(t.jawaban))op[0]=t.jawaban;return{...t,opsi:shuffle(op)};});}
function siapkanKartu(n=8){const p=pilihSegar(BANK_KARTU,n,"kartu",x=>x.a);return shuffle([...p.map((x,i)=>({id:i*2,pair:i,isi:x.a})),...p.map((x,i)=>({id:i*2+1,pair:i,isi:x.b}))]);}
function getMembers(){const raw=window.__GAME_MEMBERS__;if(!raw||!raw.length)return [];return raw;}

/* ── GLOBAL STYLES ─────────────────────────────────────────── */
const GStyles = () => (
  <style>{`
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap');
    @keyframes gf-pop  {0%{transform:scale(.93);opacity:0}100%{transform:scale(1);opacity:1}}
    @keyframes gf-rise {0%{transform:translateY(16px);opacity:0}100%{transform:translateY(0);opacity:1}}
    @keyframes gf-shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}
    @keyframes gf-blink{0%,100%{opacity:.3}50%{opacity:1}}
    @keyframes gf-flip {0%{transform:rotateY(0)}100%{transform:rotateY(180deg)}}
    .gf-pop  {animation:gf-pop  .28s ease both}
    .gf-rise {animation:gf-rise .32s ease both}
    .gf-shake{animation:gf-shake .3s ease}
    .gf-btn  {transition:transform .1s ease,filter .15s ease;font-family:inherit;}
    .gf-btn:active{transform:scale(.96)}
    .gf-btn:focus-visible{outline:3px solid #FFE08A;outline-offset:2px}
    .gf-card-wrap{perspective:800px}
    .gf-card-inner{position:relative;width:100%;height:100%;transform-style:preserve-3d;transition:transform .45s ease}
    .gf-card-inner.flipped{transform:rotateY(180deg)}
    .gf-card-face{position:absolute;inset:0;backface-visibility:hidden;border-radius:12px;display:grid;place-items:center;padding:6px;text-align:center;}
    .gf-card-back{transform:rotateY(180deg)}
    @media(prefers-reduced-motion:reduce){.gf-pop,.gf-rise,.gf-shake,.gf-btn{animation:none!important;transition:none!important}}
  `}</style>
);

/* ── SHARED COMPONENTS ──────────────────────────────────────── */
function Avatar({nama,size=44,ring}){const w=warnaDari(nama);return(<div style={{width:size,height:size,borderRadius:"50%",background:`linear-gradient(135deg,${w},${w}aa)`,display:"grid",placeItems:"center",color:"#1A1340",fontWeight:800,fontSize:size*.36,flexShrink:0,border:ring?`2.5px solid ${ring}`:"none",fontFamily:"'Bricolage Grotesque',sans-serif"}}>{inisial(nama)}</div>);}

function TimerRing({ratio,danger,size=52}){const r=size/2-5,c=2*Math.PI*r;return(<svg width={size}height={size}style={{transform:"rotate(-90deg)"}}><circle cx={size/2}cy={size/2}r={r}fill="none"stroke="rgba(255,255,255,0.12)"strokeWidth="5"/><circle cx={size/2}cy={size/2}r={r}fill="none"stroke={danger?P.red:P.gold}strokeWidth="5"strokeLinecap="round"strokeDasharray={c}strokeDashoffset={c*(1-ratio)}style={{transition:"stroke-dashoffset .25s linear,stroke .3s ease"}}/></svg>);}

function TopBar({onBack,title,subtitle}){return(<div style={{display:"flex",alignItems:"center",gap:12,marginBottom:4}}><button onClick={onBack}className="gf-btn"style={{...gBtn,padding:"8px 12px"}}>← Kembali</button><div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:19,color:P.cream}}>{title}</div>{subtitle&&<div style={{color:P.muted,fontSize:12.5,fontWeight:600}}>{subtitle}</div>}</div></div>);}

function Pill({color,label,dim}){return <span style={{padding:"5px 12px",borderRadius:99,background:`${color}1f`,border:`1px solid ${color}55`,color:dim?P.muted:color,fontWeight:800,fontSize:13}}>{label}</span>;}

function OptBtn({text,idx,state,onClick,disabled,delay=0}){
  const map={idle:{bg:"rgba(255,255,255,0.05)",bd:"rgba(255,255,255,0.12)",fg:P.cream,badge:"rgba(255,255,255,0.1)"},benar:{bg:`${P.green}22`,bd:P.green,fg:"#D9FBE6",badge:P.green},salah:{bg:`${P.red}1f`,bd:P.red,fg:"#FFE0E0",badge:P.red},redup:{bg:"rgba(255,255,255,0.03)",bd:"rgba(255,255,255,0.06)",fg:"rgba(247,243,232,0.35)",badge:"rgba(255,255,255,0.05)"}};
  const s=map[state]||map.idle;const L=["A","B","C","D"][idx];
  return(<button onClick={onClick}disabled={disabled}className="gf-btn gf-rise"style={{display:"flex",alignItems:"center",gap:13,padding:"14px 16px",borderRadius:16,border:`1.5px solid ${s.bd}`,background:s.bg,color:s.fg,cursor:disabled?"default":"pointer",fontWeight:700,fontSize:15,textAlign:"left",width:"100%",animationDelay:`${delay}s`}}><span style={{width:28,height:28,borderRadius:9,background:s.badge,display:"grid",placeItems:"center",fontSize:13,fontWeight:800,flexShrink:0,color:state==="idle"?P.gold:"#1A1340"}}>{state==="benar"?"✓":state==="salah"?"✗":L}</span><span style={{flex:1}}>{text}</span></button>);
}

function ScorePill({name,val,color,win}){return(<div style={{flex:1,padding:"14px 12px",borderRadius:18,background:win?`${color}1a`:"rgba(255,255,255,0.04)",border:`1.5px solid ${win?color:"rgba(255,255,255,0.1)"}`,maxWidth:150,textAlign:"center"}}><div style={{fontSize:12.5,fontWeight:700,color:P.muted}}>{name}</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:32,color,lineHeight:1.1}}>{val}</div>{win&&<div style={{fontSize:18}}>🏆</div>}</div>);}

function Hasil({judul,skor,baris,custom,accent,onExit}){return(<div style={{padding:"40px 24px",maxWidth:460,margin:"0 auto",textAlign:"center",display:"flex",flexDirection:"column",minHeight:480,justifyContent:"center"}}><div className="gf-pop"><div style={{width:72,height:72,margin:"0 auto",borderRadius:24,background:`linear-gradient(135deg,${accent},${accent}99)`,display:"grid",placeItems:"center",boxShadow:`0 12px 40px ${accent}55`}}><span style={{fontSize:32}}>⭐</span></div><h1 style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:26,margin:"18px 0 4px",color:P.cream}}>{judul}</h1></div>{skor!=null&&<div className="gf-rise"style={{animationDelay:".1s"}}><div style={{fontSize:12,color:P.muted,fontWeight:700,letterSpacing:1}}>SKOR AKHIR</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:54,color:accent,lineHeight:1,margin:"4px 0 20px"}}>{skor}</div></div>}{custom}{baris&&<div style={{display:"grid",gap:9,marginTop:10}}>{baris.map((b,i)=><div key={i}style={{display:"flex",justifyContent:"space-between",padding:"12px 16px",borderRadius:14,background:"rgba(255,255,255,0.04)",border:"1px solid rgba(255,255,255,0.08)"}}><span style={{color:P.muted,fontWeight:600,fontSize:14}}>{b.label}</span><span style={{fontWeight:800,fontSize:15,color:P.cream}}>{b.val}</span></div>)}</div>}<button onClick={onExit}className="gf-btn"style={{marginTop:28,padding:16,borderRadius:16,border:"none",background:`linear-gradient(135deg,${accent},${accent}cc)`,color:"#1A1340",fontWeight:800,fontSize:16,cursor:"pointer",fontFamily:"'Bricolage Grotesque',sans-serif"}}>Kembali ke Menu</button></div>);}

/* ── SHARED SCREENS ─────────────────────────────────────────── */
function PilihCara({game,onBack,onPick}){return(<div style={{padding:"24px 20px",maxWidth:460,margin:"0 auto"}}><TopBar onBack={onBack}title="Cara Bermain"subtitle={`${game.judul} — pilih mode`}/><div style={{display:"grid",gap:12,marginTop:20}}>{[{id:"solo",ikon:"👤",judul:"Main Solo",desc:"Lawan waktu, kejar skor & streak"},{id:"tatap",ikon:"📱",judul:"Hadap-hadapan",desc:"Satu HP berdua, layar terbagi 2"},{id:"online",ikon:"🌐",judul:"HP Masing-masing",desc:"Beda HP, main bareng secara online"}].map((m,i)=>{const [h,setH]=useState(false);return(<button key={m.id}onClick={()=>onPick(m.id)}onMouseEnter={()=>setH(true)}onMouseLeave={()=>setH(false)}className="gf-btn gf-rise"style={{display:"flex",alignItems:"center",gap:14,padding:18,borderRadius:20,border:`1px solid ${h?game.warna:"rgba(255,255,255,0.1)"}`,background:h?"rgba(255,255,255,0.06)":"rgba(255,255,255,0.03)",cursor:"pointer",textAlign:"left",color:P.cream,animationDelay:`${i*.06}s`}}><div style={{width:50,height:50,borderRadius:16,background:`${game.warna}22`,display:"grid",placeItems:"center",fontSize:22,flexShrink:0}}>{m.ikon}</div><div style={{flex:1}}><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:17}}>{m.judul}</div><div style={{color:P.muted,fontSize:13,fontWeight:600,marginTop:2}}>{m.desc}</div></div><span style={{color:game.warna,fontSize:18}}>›</span></button>);})}</div></div>);}

function PilihLawan({game,mode,onBack,onPick}){
  const [cari,setCari]=useState("");
  const [members,setMembers]=useState(getMembers());
  useEffect(()=>{
    let batal=false;
    const muat=()=>apiGet("/game/members").then(data=>{if(!batal)setMembers(data);}).catch(()=>{});
    const id=setInterval(muat,15000);
    return()=>{batal=true;clearInterval(id);};
  },[]);
  const list=members.filter(m=>m.nama.toLowerCase().includes(cari.toLowerCase())).sort((a,b)=>b.online-a.online);
  return(<div style={{padding:"24px 20px",maxWidth:460,margin:"0 auto"}}><TopBar onBack={onBack}title="Pilih Lawan"subtitle={mode==="online"?"Hanya anggota online bisa ditantang":"Pilih lawan bermain"}/><div style={{position:"relative",marginTop:14,marginBottom:12}}><span style={{position:"absolute",left:14,top:14,fontSize:16}}>🔍</span><input value={cari}onChange={e=>setCari(e.target.value)}placeholder="Cari anggota…"style={{width:"100%",boxSizing:"border-box",padding:"12px 14px 12px 40px",borderRadius:14,border:"1px solid rgba(255,255,255,0.12)",background:"rgba(255,255,255,0.04)",color:P.cream,fontFamily:"inherit",fontWeight:600,fontSize:14.5,outline:"none"}}/></div><div style={{display:"grid",gap:8}}>{list.map((m,i)=>{const bisa=mode!=="online"||m.online;return(<button key={m.id||i}disabled={!bisa}onClick={()=>bisa&&onPick(m)}className="gf-btn gf-rise"style={{display:"flex",alignItems:"center",gap:12,padding:13,borderRadius:16,border:"1px solid rgba(255,255,255,0.09)",background:"rgba(255,255,255,0.03)",cursor:bisa?"pointer":"default",color:P.cream,textAlign:"left",opacity:bisa?1:.4,animationDelay:`${i*.04}s`}}><div style={{position:"relative"}}><Avatar nama={m.nama}/><span style={{position:"absolute",right:-1,bottom:-1,width:11,height:11,borderRadius:99,background:m.online?P.green:"#6B6391",border:`2px solid ${P.night}`}}/></div><div style={{flex:1}}><div style={{fontWeight:800,fontSize:15}}>{m.nama}</div><div style={{color:P.muted,fontSize:12,fontWeight:600}}>{m.online?"Online":"Offline"} · {m.menang||0}M/{m.kalah||0}K</div></div>{bisa&&<span style={{fontSize:12,fontWeight:800,color:"#1A1340",background:game.warna,padding:"6px 14px",borderRadius:99}}>Pilih</span>}</button>);})}{!list.length&&<div style={{textAlign:"center",color:P.muted,fontWeight:600,padding:30}}>Tidak ada anggota ditemukan.</div>}</div></div>);
}

/* ── PUSHER HELPER ───────────────────────────────────────────── */
function getPusher(){
  if(window.__PUSHER_INSTANCE__)return window.__PUSHER_INSTANCE__;
  const cfg=window.__PUSHER_CONFIG__||{};
  if(!cfg.key){console.error("[Game] PUSHER_APP_KEY tidak terkonfigurasi — mode online tidak akan realtime.");return null;}
  const p=new window.Pusher(cfg.key,{
    cluster:cfg.cluster||"ap1",
    authEndpoint:"/broadcasting/auth",
    auth:{headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]')?.content||""}},
  });
  p.connection.bind("error",(e)=>console.error("[Game] Pusher connection error:",e));
  p.connection.bind("state_change",(s)=>console.log("[Game] Pusher state:",s.previous,"→",s.current));
  window.__PUSHER_INSTANCE__=p;
  return window.__PUSHER_INSTANCE__;
}

async function apiPost(url,data){
  const token=document.querySelector('meta[name="csrf-token"]')?.content||"";
  const r=await fetch(url,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":token,"Accept":"application/json"},body:JSON.stringify(data)});
  if(!r.ok)throw new Error(await r.text());
  return r.json();
}

async function apiGet(url){
  const r=await fetch(url,{headers:{"Accept":"application/json"}});
  if(!r.ok)throw new Error(await r.text());
  return r.json();
}

/* ── LOBI ONLINE (realtime via Pusher) ──────────────────────── */
function LobiOnline({lawan,game,sessionCode,onBack,onMulai,onDeclined}){
  const [fase,setFase]=useState(sessionCode?"tunggu":"kirim");
  const [kode,setKode]=useState(sessionCode||null);
  const pusherRef=useRef(null);
  const channelRef=useRef(null);
  const pollRef=useRef(null);

  useEffect(()=>{
    let cancelled=false;
    const pusher=getPusher();

    async function kirim(){
      try{
        let kodeAktif=sessionCode;
        if(!kodeAktif){
          const res=await apiPost("/game/challenge",{opponent_id:lawan.id,game_type:game.id});
          if(cancelled)return;
          kodeAktif=res.session_code;
          setKode(kodeAktif);
          setFase("tunggu");
        }
        // Dengarkan channel sesi untuk event started/declined (kalau Pusher tersedia)
        if(pusher){
          const ch=pusher.subscribe("private-game-session."+kodeAktif);
          channelRef.current=ch;
          ch.bind("started",()=>{if(!cancelled){setFase("diterima");setTimeout(()=>onMulai(kodeAktif),1000);}});
          ch.bind("move",(d)=>{if(d?.payload?.type==="declined"&&!cancelled){setFase("ditolak");setTimeout(onDeclined,2000);}});
        }
        // Fallback polling — kalau event Pusher "started" terlewat (race antara
        // subscribe & lawan menerima), status sesi tetap kedeteksi lewat polling.
        pollRef.current=setInterval(async()=>{
          if(cancelled)return;
          try{
            const s=await apiGet("/game/session/"+kodeAktif);
            if(cancelled)return;
            if(s.status==="active"){clearInterval(pollRef.current);setFase("diterima");setTimeout(()=>onMulai(kodeAktif),800);}
            else if(s.status==="declined"){clearInterval(pollRef.current);setFase("ditolak");setTimeout(onDeclined,2000);}
          }catch(e){/* abaikan, coba lagi di polling berikutnya */}
        },2500);
      }catch(e){
        if(!cancelled)setFase("error");
      }
    }
    kirim();

    return()=>{
      cancelled=true;
      clearInterval(pollRef.current);
      if(channelRef.current&&pusher)pusher.unsubscribe(channelRef.current.name);
    };
  },[]);

  const teks={kirim:"Mengirim tantangan…",tunggu:`Menunggu ${lawan.nama} menerima…`,diterima:"Tantangan diterima! 🎉",ditolak:"Tantangan ditolak.",error:"Gagal mengirim tantangan."};
  const warna={diterima:P.green,ditolak:P.red,error:P.red};

  return(
    <div style={{padding:"24px 20px",minHeight:400,maxWidth:460,margin:"0 auto",display:"flex",flexDirection:"column"}}>
      <TopBar onBack={onBack}title="Menghubungkan…"/>
      <div style={{flex:1,display:"flex",flexDirection:"column",alignItems:"center",justifyContent:"center",gap:24}}>
        <div style={{display:"flex",alignItems:"center",gap:20}}>
          <div style={{textAlign:"center"}}><Avatar nama={(window.__GAME_USER__||{nama:"Kamu"}).nama}size={60}ring={P.gold}/><div style={{marginTop:8,fontWeight:800,fontSize:13,color:P.cream}}>Kamu</div></div>
          <div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:20,color:P.muted}}>VS</div>
          <div style={{textAlign:"center"}}><Avatar nama={lawan.nama}size={60}ring={fase==="diterima"?P.green:game.warna}/><div style={{marginTop:8,fontWeight:800,fontSize:13,color:P.cream}}>{lawan.nama}</div></div>
        </div>
        <div key={fase}className="gf-pop"style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:18,color:warna[fase]||P.cream}}>{teks[fase]}</div>
        {(fase==="kirim"||fase==="tunggu")&&<div style={{display:"flex",gap:6}}>{[0,1,2].map(i=><div key={i}style={{width:8,height:8,borderRadius:99,background:P.gold,animation:`gf-blink 1.2s ${i*.4}s ease infinite`}}/>)}</div>}
        {kode&&fase==="tunggu"&&<div style={{fontSize:11,color:P.muted,fontWeight:600}}>Kode sesi: {kode}</div>}
      </div>
    </div>
  );
}

/* ── NOTIF TANTANGAN MASUK ──────────────────────────────────── */
function NotifTantangan({notif,onTerima,onTolak}){
  return(
    <div className="gf-pop"style={{position:"fixed",bottom:90,left:"50%",transform:"translateX(-50%)",width:"calc(100% - 32px)",maxWidth:440,zIndex:9999,padding:"16px 18px",borderRadius:20,background:"#241A57",border:`1.5px solid ${P.gold}`,boxShadow:"0 8px 32px rgba(0,0,0,0.5)"}}>
      <div style={{fontWeight:800,fontSize:14,color:P.gold,marginBottom:4}}>🎮 Tantangan Masuk!</div>
      <div style={{fontWeight:700,fontSize:14,color:P.cream,marginBottom:12}}><b>{notif.challenger_name}</b> mengajakmu main <b>{notif.game_type}</b></div>
      <div style={{display:"flex",gap:10}}>
        <button onClick={onTerima}className="gf-btn"style={{flex:1,padding:"10px",borderRadius:12,border:"none",background:P.green,color:"#1A1340",fontWeight:800,fontSize:14,cursor:"pointer"}}>✓ Terima</button>
        <button onClick={onTolak}className="gf-btn"style={{flex:1,padding:"10px",borderRadius:12,border:`1px solid ${P.red}`,background:"transparent",color:P.red,fontWeight:800,fontSize:14,cursor:"pointer"}}>✗ Tolak</button>
      </div>
    </div>
  );
}

/* ── PAPAN PERINGKAT ─────────────────────────────────────────── */
function PapanPeringkat({onBack}){
  const [tab,setTab]=useState("semua");
  const tabs=[{id:"semua",label:"Semua"},{id:"kuis",label:"⚡ Kuis"},{id:"susun",label:"📖 Susun"},{id:"tebak",label:"🔍 Tebak"},{id:"memory",label:"🃏 Memory"}];
  const sorted=[...getLeaderboard()].sort((a,b)=>(tab==="semua"?b.poin:b.detail[tab])-(tab==="semua"?a.poin:a.detail[tab]));
  const medals=["🥇","🥈","🥉"];
  return(<div style={{padding:"20px 20px 32px",maxWidth:460,margin:"0 auto"}}><TopBar onBack={onBack}title="Papan Peringkat"subtitle="Minggu ini · Reset tiap Senin"/><div style={{display:"flex",gap:6,marginTop:16,overflowX:"auto",paddingBottom:4}}>{tabs.map(t=><button key={t.id}onClick={()=>setTab(t.id)}className="gf-btn"style={{padding:"7px 14px",borderRadius:99,flexShrink:0,border:`1px solid ${tab===t.id?P.gold:"rgba(255,255,255,0.12)"}`,background:tab===t.id?`${P.gold}22`:"rgba(255,255,255,0.04)",color:tab===t.id?P.gold:P.muted,fontWeight:700,fontSize:13}}>{t.label}</button>)}</div>{sorted.length===0?<div style={{textAlign:"center",padding:"40px 20px",color:P.muted,fontSize:14}}>Belum ada yang bermain minggu ini</div>:<div style={{display:"grid",gap:9,marginTop:16}}>{sorted.map((m,i)=>{const poin=tab==="semua"?m.poin:m.detail[tab];return(<div key={i}className="gf-rise"style={{display:"flex",alignItems:"center",gap:12,padding:"13px 16px",borderRadius:16,border:`1px solid ${i<3?"rgba(245,196,81,0.3)":"rgba(255,255,255,0.08)"}`,background:i<3?`${P.gold}0a`:"rgba(255,255,255,0.03)",animationDelay:`${i*.04}s`}}><div style={{width:26,textAlign:"center",fontSize:20,fontWeight:800,flexShrink:0}}>{medals[i]||<span style={{fontSize:14,color:P.muted,fontWeight:800}}>#{i+1}</span>}</div><Avatar nama={m.nama}size={38}/><div style={{flex:1}}><div style={{fontWeight:800,fontSize:15,color:P.cream}}>{m.nama}</div></div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:20,color:i===0?P.gold:P.cream}}>{poin.toLocaleString()}</div></div>);})}</div>}</div>);
}

/* ════════════════════════════════════════════════════════════
   GAME 1: KUIS ADU CEPAT
════════════════════════════════════════════════════════════ */
function optState(i,pilih,benar){if(pilih===null)return"idle";if(i===benar)return"benar";if(i===pilih)return"salah";return"redup";}

function KuisSolo({onExit}){
  const [soal]=useState(()=>siapkanSoal(10));
  const [idx,setIdx]=useState(0);const [skor,setSkor]=useState(0);const [streak,setStreak]=useState(0);const [best,setBest]=useState(0);
  const [benarTotal,setBenarTotal]=useState(0);const [pilih,setPilih]=useState(null);const [waktu,setWaktu]=useState(15);
  const [selesai,setSelesai]=useState(false);const [poin,setPoin]=useState(0);const timerRef=useRef();
  const s=soal[idx];
  const lanjut=useCallback(()=>{if(idx+1>=soal.length)setSelesai(true);else{setIdx(i=>i+1);setPilih(null);setWaktu(15);}},[idx,soal.length]);
  const jawab=useCallback(i=>{if(pilih!==null)return;clearInterval(timerRef.current);setPilih(i);if(i===s.benar){const p=100+Math.round((waktu/15)*100)+streak*20;setPoin(p);setSkor(sc=>sc+p);setBenarTotal(b=>b+1);setStreak(st=>{const n=st+1;setBest(bs=>Math.max(bs,n));return n;});}else{setPoin(0);setStreak(0);}setTimeout(lanjut,1400);},[pilih,s,waktu,streak,lanjut]);
  useEffect(()=>{if(selesai||pilih!==null)return;timerRef.current=setInterval(()=>{setWaktu(w=>{if(w<=0.1){clearInterval(timerRef.current);setPilih(-1);setStreak(0);setPoin(0);setTimeout(lanjut,1400);return 0;}return+(w-.1).toFixed(1);});},100);return()=>clearInterval(timerRef.current);},[idx,pilih,selesai,lanjut]);
  if(selesai)return<Hasil judul="Selesai! 🎉"skor={skor}accent={P.gold}onExit={onExit}baris={[{label:"Jawaban benar",val:`${benarTotal}/10`},{label:"Streak terbaik",val:`${best} 🔥`},{label:"Akurasi",val:`${Math.round(benarTotal/10*100)}%`}]}/>;
  const ratio=waktu/15;
  return(<div style={{padding:"18px 20px 28px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center"}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><div style={{display:"flex",gap:8}}><Pill color={P.gold}label={`${skor} pts`}/><Pill color={P.red}label={`${streak}🔥`}dim={streak===0}/></div></div><div style={{marginTop:16}}><div style={{display:"flex",justifyContent:"space-between",fontSize:12.5,fontWeight:700,color:P.muted,marginBottom:7}}><span>Soal {idx+1}/10</span><span style={{color:ratio<.3?P.red:P.gold}}>{Math.ceil(waktu)} dtk</span></div><div style={{height:6,background:"rgba(255,255,255,0.1)",borderRadius:99,overflow:"hidden"}}><div style={{height:"100%",width:`${ratio*100}%`,background:ratio<.3?P.red:`linear-gradient(90deg,${P.gold},${P.goldSoft})`,transition:"width .1s linear"}}/></div></div><div key={idx}className="gf-pop"style={{marginTop:22}}><div style={{fontSize:12,fontWeight:700,color:P.gold,letterSpacing:1,textTransform:"uppercase"}}>Pertanyaan</div><h2 style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:22,lineHeight:1.25,margin:"8px 0 0",color:P.cream}}>{s.q}</h2></div><div style={{display:"grid",gap:10,marginTop:20}}>{s.opsi.map((op,i)=><OptBtn key={i}text={op}idx={i}state={optState(i,pilih,s.benar)}onClick={()=>jawab(i)}disabled={pilih!==null}delay={i*.05}/>)}</div><div style={{minHeight:30,marginTop:12,textAlign:"center"}}>{pilih!==null&&<div className="gf-pop"style={{fontWeight:800,fontSize:16,color:poin>0?P.green:P.red,fontFamily:"'Bricolage Grotesque',sans-serif"}}>{poin>0?`+${poin} poin!`:pilih===-1?"Waktu habis ⏱":"Belum tepat"}</div>}</div></div>);
}

function KuisTatap({lawan,onExit}){
  const [soal]=useState(()=>siapkanSoal(7));const [idx,setIdx]=useState(0);const [skor,setSkor]=useState({p1:0,p2:0});
  const [lock,setLock]=useState({p1:false,p2:false});const [pilih,setPilih]=useState({p1:null,p2:null});
  const [winner,setWinner]=useState(null);const [waktu,setWaktu]=useState(12);const [selesai,setSelesai]=useState(false);const timerRef=useRef();
  const s=soal[idx];
  const lanjut=useCallback(()=>{if(idx+1>=soal.length)setSelesai(true);else{setIdx(i=>i+1);setLock({p1:false,p2:false});setPilih({p1:null,p2:null});setWinner(null);setWaktu(12);}},[idx,soal.length]);
  const jawab=useCallback((pem,i)=>{if(winner||lock[pem]||pilih[pem]!==null)return;setPilih(p=>({...p,[pem]:i}));if(i===s.benar){clearInterval(timerRef.current);const p=100+Math.round((waktu/12)*50);setSkor(sc=>({...sc,[pem]:sc[pem]+p}));setWinner(pem);setTimeout(lanjut,1500);}else setLock(l=>({...l,[pem]:true}));},[winner,lock,pilih,s,waktu,lanjut]);
  useEffect(()=>{if(selesai||winner)return;timerRef.current=setInterval(()=>{setWaktu(w=>{if(w<=0.1){clearInterval(timerRef.current);setWinner("seri");setTimeout(lanjut,1500);return 0;}return+(w-.1).toFixed(1);});},100);return()=>clearInterval(timerRef.current);},[idx,winner,selesai,lanjut]);
  if(selesai){const w=skor.p1===skor.p2?"Seri!":skor.p1>skor.p2?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.p1>=skor.p2?P.p1:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name="Kamu"val={skor.p1}color={P.p1}win={skor.p1>=skor.p2}/><ScorePill name={lawan.nama}val={skor.p2}color={P.p2}win={skor.p2>=skor.p1}/></div>}/>;}
  const ratio=waktu/12;
  const Panel=({pem,nama,color,flip})=>{const w=winner===pem;return(<div style={{flex:1,padding:"14px 16px",display:"flex",flexDirection:"column",transform:flip?"rotate(180deg)":"none",background:w?`${color}14`:"transparent",transition:"background .3s"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center",marginBottom:10}}><div style={{display:"flex",alignItems:"center",gap:8}}><Avatar nama={nama}size={26}/><span style={{fontWeight:800,fontSize:13,color}}>{nama}</span></div><span style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:18,color}}>{skor[pem]}</span></div><h3 style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:16,lineHeight:1.25,margin:"0 0 10px",color:P.cream}}>{s.q}</h3><div style={{display:"grid",gridTemplateColumns:"1fr 1fr",gap:7,flex:1}}>{s.opsi.map((op,i)=>{let st="idle";if(winner){st=i===s.benar?"benar":i===pilih[pem]?"salah":"redup";}else if(pilih[pem]===i)st="salah";else if(lock[pem])st="redup";return(<button key={i}onClick={()=>jawab(pem,i)}disabled={!!winner||lock[pem]||pilih[pem]!==null}className="gf-btn"style={{padding:"11px",borderRadius:12,border:`1.5px solid ${st==="benar"?P.green:st==="salah"?P.red:"rgba(255,255,255,0.12)"}`,background:st==="benar"?`${P.green}22`:st==="salah"?`${P.red}1f`:"rgba(255,255,255,0.05)",color:P.cream,cursor:"pointer",fontSize:13.5,fontWeight:700,lineHeight:1.2,minHeight:48}}>{op}</button>);})}</div>{w&&<div className="gf-pop"style={{textAlign:"center",marginTop:8,fontWeight:800,color,fontSize:14}}>Tercepat! ⚡</div>}{winner&&!w&&winner!=="seri"&&<div style={{textAlign:"center",marginTop:8,color:P.muted,fontSize:13,fontWeight:700}}>Keduluan…</div>}{winner==="seri"&&<div style={{textAlign:"center",marginTop:8,color:P.muted,fontSize:13,fontWeight:700}}>Waktu habis</div>}{lock[pem]&&!winner&&<div className="gf-shake"style={{textAlign:"center",marginTop:8,color:P.red,fontSize:13,fontWeight:700}}>Terkunci ronde ini ✗</div>}</div>);};
  return(<div style={{display:"flex",flexDirection:"column",minHeight:560}}><Panel pem="p2"nama={lawan.nama}color={P.p2}flip/><div style={{display:"flex",alignItems:"center",justifyContent:"center",gap:14,padding:"8px 16px",background:"rgba(0,0,0,0.3)",position:"relative"}}><button onClick={onExit}className="gf-btn"style={{...gBtn,padding:"5px 10px",position:"absolute",left:10}}>←</button><span style={{fontSize:11,fontWeight:800,color:P.muted,letterSpacing:1}}>RONDE {idx+1}/{soal.length}</span><div style={{position:"relative",display:"grid",placeItems:"center"}}><TimerRing ratio={ratio}danger={ratio<.3}size={50}/><div style={{position:"absolute",fontWeight:800,fontSize:15,fontFamily:"'Bricolage Grotesque',sans-serif",color:ratio<.3?P.red:P.cream}}>{Math.ceil(waktu)}</div></div><span style={{fontSize:11,fontWeight:800,color:P.muted,letterSpacing:1}}>ADU CEPAT</span></div><Panel pem="p1"nama="Kamu"color={P.p1}/></div>);
}

/* ── HOOK: SINKRONISASI GAME ONLINE VIA PUSHER ──────────────── */
function useOnlineGame(sessionCode,onMove,onEnded){
  const pusherRef=useRef(null);
  const chRef=useRef(null);
  // onMove/onEnded dibuat ulang tiap render (menutup state ronde saat ini).
  // Simpan versi TERBARU di ref supaya listener Pusher (dipasang sekali saat
  // mount) selalu memanggil closure terkini — bukan closure basi dari ronde
  // pertama, yang sebelumnya bikin game berhenti merespons di tengah main.
  const onMoveRef=useRef(onMove);
  const onEndedRef=useRef(onEnded);
  onMoveRef.current=onMove;
  onEndedRef.current=onEnded;

  useEffect(()=>{
    if(!sessionCode)return;
    const pusher=getPusher();
    if(!pusher)return;
    const ch=pusher.subscribe("private-game-session."+sessionCode);
    chRef.current=ch;
    ch.bind("move",(d)=>onMoveRef.current&&onMoveRef.current(d));
    ch.bind("ended",(d)=>onEndedRef.current&&onEndedRef.current(d));
    return()=>{pusher.unsubscribe("private-game-session."+sessionCode);};
  },[sessionCode]);

  const sendMove=useCallback(async(payload)=>{
    if(!sessionCode)return;
    try{await apiPost("/game/move",{session_code:sessionCode,payload});}catch(e){}
  },[sessionCode]);

  const sendFinished=useCallback(async(score)=>{
    if(!sessionCode)return;
    try{await apiPost("/game/move",{session_code:sessionCode,payload:{finished:true,score}});}catch(e){}
  },[sessionCode]);

  return{sendMove,sendFinished};
}

/* ── SKOR BAR ONLINE ────────────────────────────────────────── */
function SkorBarOnline({namaSaya,namaLawan,skorSaya,skorLawan,tengah}){
  return(
    <div style={{display:"flex",alignItems:"center",justifyContent:"space-between",marginTop:14,gap:10}}>
      <div style={{display:"flex",alignItems:"center",gap:9,flex:1}}><Avatar nama={namaSaya}size={38}ring={P.gold}/><div><div style={{fontWeight:800,fontSize:13,color:P.gold}}>{namaSaya}</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:20,color:P.cream}}>{skorSaya}</div></div></div>
      {tengah}
      <div style={{display:"flex",alignItems:"center",gap:9,flex:1,justifyContent:"flex-end"}}><div style={{textAlign:"right"}}><div style={{fontWeight:800,fontSize:13,color:P.p2}}>{namaLawan}</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:20,color:P.cream}}>{skorLawan}</div></div><Avatar nama={namaLawan}size={38}ring={P.p2}/></div>
    </div>
  );
}

function KuisOnline({lawan,sessionCode,onExit}){
  const namaSaya=(window.__GAME_USER__||{nama:"Kamu"}).nama;
  const [soal]=useState(()=>siapkanSoalSeed(7,sessionCode+":kuis"));
  const [idx,setIdx]=useState(0);
  const [skor,setSkor]=useState({you:0,op:0});
  const [pilih,setPilih]=useState(null);
  const [youLock,setYouLock]=useState(false);
  const [opStatus,setOpStatus]=useState("thinking");
  const [hasil,setHasil]=useState(null);
  const [waktu,setWaktu]=useState(12);
  const [selesai,setSelesai]=useState(false);
  const timerRef=useRef();
  const resolvedRef=useRef(false);
  const waktuRef=useRef(12);
  const youLockRef=useRef(false);
  const s=soal[idx];

  const lanjut=useCallback(()=>{
    if(idx+1>=soal.length)setSelesai(true);
    else{setIdx(i=>i+1);setPilih(null);setYouLock(false);setOpStatus("thinking");setHasil(null);setWaktu(12);}
  },[idx,soal.length]);

  const resolve=useCallback((w,youSkor)=>{
    if(resolvedRef.current)return;
    resolvedRef.current=true;
    clearInterval(timerRef.current);
    setHasil(w);
    if(w==="you"){setSkor(s=>({...s,you:s.you+youSkor}));}
    setTimeout(lanjut,1700);
  },[lanjut]);

  const {sendMove,sendFinished}=useOnlineGame(sessionCode,(d)=>{
    if(d.role==="opponent"||d.user_id!==window.__GAME_USER__?.id){
      const p=d.payload||{};
      if(p.type==="answer_correct"){setOpStatus("answered");resolve("op",0);setSkor(s=>({...s,op:d.score_opponent??s.op+100}));}
      else if(p.type==="answer_wrong"){setOpStatus("wrong");}
    }
  },(d)=>{setSkor({you:d.score_challenger,op:d.score_opponent});setSelesai(true);});

  const jawab=useCallback(i=>{
    if(resolvedRef.current||pilih!==null||youLockRef.current)return;
    setPilih(i);
    const poin=100+Math.round((waktuRef.current/12)*50);
    if(i===s.benar){
      sendMove({type:"answer_correct",ronde:idx,score:skor.you+poin});
      resolve("you",poin);
    }else{
      youLockRef.current=true;
      setYouLock(true);
      sendMove({type:"answer_wrong",ronde:idx,score:skor.you});
    }
  },[pilih,s,resolve,idx,skor.you,sendMove]);

  useEffect(()=>{
    resolvedRef.current=false;youLockRef.current=false;waktuRef.current=12;
  },[idx]);

  useEffect(()=>{
    if(selesai)return;
    timerRef.current=setInterval(()=>{setWaktu(w=>{const n=w<=0.1?0:+(w-.1).toFixed(1);waktuRef.current=n;if(n===0&&!resolvedRef.current)resolve("seri",0);return n;});},100);
    return()=>clearInterval(timerRef.current);
  },[idx,selesai,resolve]);

  useEffect(()=>{if(selesai)sendFinished(skor.you);},[selesai]);

  if(selesai){const w=skor.you===skor.op?"Seri!":skor.you>skor.op?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.you>=skor.op?P.gold:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name={namaSaya}val={skor.you}color={P.gold}win={skor.you>=skor.op}/><ScorePill name={lawan.nama}val={skor.op}color={P.p2}win={skor.op>=skor.you}/></div>}/>;}
  const ratio=waktu/12;
  return(<div style={{padding:"18px 20px 26px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center"}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><span style={{fontSize:11,fontWeight:800,color:P.muted,letterSpacing:1}}>RONDE {idx+1}/{soal.length}</span></div><SkorBarOnline namaSaya={namaSaya}namaLawan={lawan.nama}skorSaya={skor.you}skorLawan={skor.op}tengah={<div style={{position:"relative",display:"grid",placeItems:"center",flexShrink:0}}><TimerRing ratio={ratio}danger={ratio<.3}size={48}/><div style={{position:"absolute",fontWeight:800,fontSize:14,color:ratio<.3?P.red:P.cream}}>{Math.ceil(waktu)}</div></div>}/><div style={{marginTop:10,padding:"9px 14px",borderRadius:12,background:"rgba(255,255,255,0.04)",border:"1px solid rgba(255,255,255,0.08)",display:"flex",alignItems:"center",gap:9,fontSize:13,fontWeight:700}}><span style={{width:8,height:8,borderRadius:99,background:P.p2,animation:opStatus==="thinking"?"gf-blink 1s ease infinite":"none",display:"inline-block"}}/>{hasil?<span style={{color:hasil==="op"?P.p2:P.muted}}>{hasil==="op"?`${lawan.nama} lebih cepat`:hasil==="you"?`${lawan.nama} keduluan kamu`:"Ronde seri"}</span>:opStatus==="thinking"?<span style={{color:P.muted}}>{lawan.nama} sedang menjawab…</span>:<span style={{color:P.red}}>{lawan.nama} salah — peluangmu!</span>}</div><div key={idx}className="gf-pop"style={{marginTop:18}}><div style={{fontSize:12,fontWeight:700,color:P.gold,letterSpacing:1,textTransform:"uppercase"}}>Pertanyaan</div><h2 style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:21,lineHeight:1.25,margin:"7px 0 0",color:P.cream}}>{s.q}</h2></div><div style={{display:"grid",gap:9,marginTop:16}}>{s.opsi.map((op,i)=>{let st="idle";if(hasil){st=i===s.benar?"benar":i===pilih?"salah":"redup";}else if(pilih===i)st="salah";else if(youLock)st="redup";return<OptBtn key={i}text={op}idx={i}state={st}onClick={()=>jawab(i)}disabled={hasil!==null||youLock||pilih!==null}delay={i*.04}/>;})}</div><div style={{minHeight:26,marginTop:10,textAlign:"center"}}>{hasil&&<div className="gf-pop"style={{fontWeight:800,fontSize:15,color:hasil==="you"?P.green:hasil==="op"?P.red:P.muted}}>{hasil==="you"?"Kamu tercepat! ⚡":hasil==="op"?"Keduluan lawan…":"Ronde seri"}</div>}{youLock&&!hasil&&<div className="gf-shake"style={{fontWeight:700,fontSize:13,color:P.red}}>Jawabanmu salah — terkunci ronde ini ✗</div>}</div></div>);
}

/* ════════════════════════════════════════════════════════════
   GAME 2: SUSUN AYAT
════════════════════════════════════════════════════════════ */
function WordArea({kata,onKlik,disabled,accent}){return(<div style={{display:"flex",flexWrap:"wrap",gap:7,minHeight:48,padding:"10px 12px",borderRadius:14,border:"1px dashed rgba(255,255,255,0.18)",background:"rgba(255,255,255,0.03)"}}>{kata.map((k,i)=><button key={i}onClick={()=>!disabled&&onKlik(i)}disabled={disabled}className="gf-btn"style={{padding:"8px 14px",borderRadius:10,border:`1px solid ${accent}55`,background:`${accent}15`,color:P.cream,fontSize:14,fontWeight:700,cursor:disabled?"default":"pointer"}}>{k}</button>)}{!kata.length&&<span style={{color:P.muted,fontSize:13,fontWeight:600}}>Tap kata di bawah untuk menyusun…</span>}</div>);}

function SusunSolo({onExit}){
  const [ayat]=useState(()=>siapkanAyat(5));const [idx,setIdx]=useState(0);const [skor,setSkor]=useState(0);const [benarTotal,setBenarTotal]=useState(0);
  const [disusun,setDisusun]=useState([]);const [bank,setBank]=useState([]);const [waktu,setWaktu]=useState(30);const [selesai,setSelesai]=useState(false);
  const [flash,setFlash]=useState(null);const [streak,setStreak]=useState(0);const timerRef=useRef();
  const a=ayat[idx];
  useEffect(()=>{setBank(a.acak.map((k,i)=>({kata:k,origIdx:i})));setDisusun([]);},[idx]);
  const lanjut=useCallback((berhasil)=>{clearInterval(timerRef.current);if(berhasil){const p=100+Math.round((waktu/30)*150)+streak*25;setSkor(s=>s+p);setBenarTotal(b=>b+1);setStreak(s=>s+1);setFlash({ok:true,pesan:`+${p} poin!`});}else{setStreak(0);setFlash({ok:false,pesan:"Waktu habis ⏱"});}setTimeout(()=>{setFlash(null);if(idx+1>=ayat.length)setSelesai(true);else{setIdx(i=>i+1);setWaktu(30);}},1400);},[waktu,streak,idx,ayat.length]);
  useEffect(()=>{if(selesai||flash)return;timerRef.current=setInterval(()=>{setWaktu(w=>{if(w<=0.1){clearInterval(timerRef.current);lanjut(false);return 0;}return+(w-.1).toFixed(1);});},100);return()=>clearInterval(timerRef.current);},[idx,selesai,flash,lanjut]);
  const tambah=i=>{if(flash)return;const item=bank[i];const nd=[...disusun,item];setDisusun(nd);setBank(b=>b.filter((_,j)=>j!==i));if(nd.length===a.kata.length){const benar=nd.every((it,j)=>it.kata===a.kata[j]);if(benar)lanjut(true);else setFlash({ok:false,pesan:"Susunan belum tepat 🤔"});}};
  const hapus=i=>{const item=disusun[i];setDisusun(d=>d.filter((_,j)=>j!==i));setBank(b=>[...b,item]);setFlash(null);};
  if(selesai)return<Hasil judul="Selesai! 📖"skor={skor}accent={P.p2}onExit={onExit}baris={[{label:"Ayat tersusun",val:`${benarTotal}/5`},{label:"Streak terbaik",val:`${Math.max(benarTotal,0)} 🔥`},{label:"Total poin",val:skor.toLocaleString()}]}/>;
  const ratio=waktu/30;
  return(<div style={{padding:"18px 20px 28px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center"}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><Pill color={P.p2}label={`${skor} pts`}/></div><div style={{marginTop:14,marginBottom:16}}><div style={{display:"flex",justifyContent:"space-between",fontSize:12.5,fontWeight:700,color:P.muted,marginBottom:6}}><span>Ayat {idx+1}/5 · <span style={{color:P.p2}}>{a.ref}</span></span><span style={{color:ratio<.3?P.red:P.p2}}>{Math.ceil(waktu)} dtk</span></div><div style={{height:5,background:"rgba(255,255,255,0.1)",borderRadius:99}}><div style={{height:"100%",width:`${ratio*100}%`,background:ratio<.3?P.red:P.p2,transition:"width .1s linear"}}/></div></div><div style={{marginBottom:10}}><div style={{fontSize:12,fontWeight:700,color:P.p2,letterSpacing:1,textTransform:"uppercase",marginBottom:8}}>Susunanmu:</div><WordArea kata={disusun.map(x=>x.kata)}onKlik={hapus}disabled={!!flash}accent={P.p2}/></div><div><div style={{fontSize:12,fontWeight:700,color:P.muted,letterSpacing:1,textTransform:"uppercase",marginBottom:8}}>Bank Kata:</div><WordArea kata={bank.map(x=>x.kata)}onKlik={tambah}disabled={!!flash}accent={P.muted}/></div><div style={{minHeight:30,marginTop:12,textAlign:"center"}}>{flash&&<div className="gf-pop"style={{fontWeight:800,fontSize:16,color:flash.ok?P.green:P.red,fontFamily:"'Bricolage Grotesque',sans-serif"}}>{flash.pesan}</div>}</div></div>);
}

function SusunTatap({lawan,onExit}){
  const [ayat]=useState(()=>siapkanAyat(5));const [idx,setIdx]=useState(0);const [skor,setSkor]=useState({p1:0,p2:0});
  const [state,setState]=useState({p1:{disusun:[],bank:[]},p2:{disusun:[],bank:[]}});
  const [waktu,setWaktu]=useState(25);const [winner,setWinner]=useState(null);const [selesai,setSelesai]=useState(false);const timerRef=useRef();
  const a=ayat[idx];
  useEffect(()=>{const bank=a.acak.map((k,i)=>({kata:k,origIdx:i}));setState({p1:{disusun:[],bank:[...bank]},p2:{disusun:[],bank:[...bank]}});},[idx]);
  const lanjut=useCallback(w=>{if(idx+1>=ayat.length)setSelesai(true);else{setIdx(i=>i+1);setWinner(null);setWaktu(25);};if(w)setSkor(s=>({...s,[w]:s[w]+100+Math.round((waktu/25)*80)}));},[idx,ayat.length,waktu]);
  useEffect(()=>{if(selesai||winner)return;timerRef.current=setInterval(()=>{setWaktu(w=>{if(w<=0.1){clearInterval(timerRef.current);setWinner("seri");setTimeout(()=>lanjut(null),1500);return 0;}return+(w-.1).toFixed(1);});},100);return()=>clearInterval(timerRef.current);},[idx,winner,selesai,lanjut]);
  const tambah=(pem,i)=>{if(winner)return;const cur=state[pem];const item=cur.bank[i];const nd=[...cur.disusun,item];const nb=cur.bank.filter((_,j)=>j!==i);setState(st=>({...st,[pem]:{disusun:nd,bank:nb}}));if(nd.length===a.kata.length&&nd.every((it,j)=>it.kata===a.kata[j])){clearInterval(timerRef.current);setWinner(pem);setTimeout(()=>lanjut(pem),1500);}};
  const hapus=(pem,i)=>{if(winner)return;setState(st=>{const cur=st[pem];const item=cur.disusun[i];return{...st,[pem]:{disusun:cur.disusun.filter((_,j)=>j!==i),bank:[...cur.bank,item]}};});};
  if(selesai){const w=skor.p1===skor.p2?"Seri!":skor.p1>skor.p2?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.p1>=skor.p2?P.p1:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name="Kamu"val={skor.p1}color={P.p1}win={skor.p1>=skor.p2}/><ScorePill name={lawan.nama}val={skor.p2}color={P.p2}win={skor.p2>=skor.p1}/></div>}/>;}
  const ratio=waktu/25;
  const Panel=({pem,nama,color,flip})=>(<div style={{flex:1,padding:"12px 16px",display:"flex",flexDirection:"column",transform:flip?"rotate(180deg)":"none",background:winner===pem?`${color}14`:"transparent"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center",marginBottom:8}}><div style={{display:"flex",alignItems:"center",gap:6}}><Avatar nama={nama}size={24}/><span style={{fontWeight:800,fontSize:13,color}}>{nama}</span></div><span style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:17,color}}>{skor[pem]}</span></div><div style={{fontSize:11,fontWeight:700,color:P.muted,marginBottom:5}}>{a.ref}</div><div style={{fontSize:11,color:P.muted,marginBottom:7}}>Susunanmu:<br/><WordArea kata={state[pem].disusun.map(x=>x.kata)}onKlik={i=>hapus(pem,i)}disabled={!!winner}accent={color}/></div><div style={{fontSize:11,color:P.muted}}>Bank Kata:<br/><WordArea kata={state[pem].bank.map(x=>x.kata)}onKlik={i=>tambah(pem,i)}disabled={!!winner}accent={P.muted}/></div>{winner===pem&&<div className="gf-pop"style={{textAlign:"center",marginTop:6,fontWeight:800,color,fontSize:13}}>Tersusun! 🎉</div>}</div>);
  return(<div style={{display:"flex",flexDirection:"column",minHeight:560}}><Panel pem="p2"nama={lawan.nama}color={P.p2}flip/><div style={{padding:"7px 14px",background:"rgba(0,0,0,0.3)",display:"flex",alignItems:"center",justifyContent:"center",gap:12,position:"relative"}}><button onClick={onExit}className="gf-btn"style={{...gBtn,padding:"4px 10px",position:"absolute",left:8}}>←</button><span style={{fontSize:11,fontWeight:800,color:P.muted}}>RONDE {idx+1}/{ayat.length}</span><div style={{position:"relative",display:"grid",placeItems:"center"}}><TimerRing ratio={ratio}danger={ratio<.3}size={44}/><div style={{position:"absolute",fontWeight:800,fontSize:13,color:ratio<.3?P.red:P.cream}}>{Math.ceil(waktu)}</div></div></div><Panel pem="p1"nama="Kamu"color={P.p1}/></div>);
}

function SusunOnline({lawan,sessionCode,onExit}){
  const namaSaya=(window.__GAME_USER__||{nama:"Kamu"}).nama;
  const [ayat]=useState(()=>siapkanAyatSeed(5,sessionCode+":susun"));
  const [idx,setIdx]=useState(0);
  const [skor,setSkor]=useState({you:0,op:0});
  const skorRef=useRef({you:0,op:0});
  const [disusun,setDisusun]=useState([]);
  const [bank,setBank]=useState([]);
  const [waktu,setWaktu]=useState(25);
  const [hasil,setHasil]=useState(null);
  const [selesai,setSelesai]=useState(false);
  const timerRef=useRef();
  const resolvedRef=useRef(false);
  const waktuRef=useRef(25);
  const a=ayat[idx];

  useEffect(()=>{skorRef.current=skor;},[skor]);

  const lanjutKe=useCallback((nextIdx)=>{
    if(nextIdx>=ayat.length)setSelesai(true);
    else{setIdx(nextIdx);setHasil(null);setWaktu(25);}
  },[ayat.length]);

  const {sendMove,sendFinished}=useOnlineGame(sessionCode,(d)=>{
    if(d.user_id!==window.__GAME_USER__?.id){
      const p=d.payload||{};
      if(p.type==="ronde_selesai"&&!resolvedRef.current){
        resolvedRef.current=true;
        clearInterval(timerRef.current);
        const newOp=p.score??skorRef.current.op+100;
        setSkor(s=>{const ns={...s,op:newOp};skorRef.current=ns;return ns;});
        setHasil("op");
        setTimeout(()=>lanjutKe(p.ronde+1),1600);
      }
    }
  },(d)=>{setSkor({you:d.score_challenger,op:d.score_opponent});setSelesai(true);});

  useEffect(()=>{
    setBank(a.acak.map((k,i)=>({kata:k,origIdx:i})));
    setDisusun([]);
    resolvedRef.current=false;
    waktuRef.current=25;
  },[idx]);

  useEffect(()=>{
    if(selesai||hasil)return;
    timerRef.current=setInterval(()=>{
      setWaktu(w=>{
        const n=w<=0.1?0:+(w-.1).toFixed(1);
        waktuRef.current=n;
        if(n===0&&!resolvedRef.current){
          resolvedRef.current=true;
          setHasil("seri");
          sendMove({type:"ronde_selesai",ronde:idx,score:skorRef.current.you});
          setTimeout(()=>lanjutKe(idx+1),1600);
        }
        return n;
      });
    },100);
    return()=>clearInterval(timerRef.current);
  },[idx,selesai,hasil,lanjutKe,sendMove]);

  const tambah=i=>{
    if(hasil)return;
    const item=bank[i];
    const nd=[...disusun,item];
    setDisusun(nd);
    setBank(b=>b.filter((_,j)=>j!==i));
    if(nd.length===a.kata.length&&nd.every((it,j)=>it.kata===a.kata[j])){
      if(!resolvedRef.current){
        resolvedRef.current=true;
        clearInterval(timerRef.current);
        const p=100+Math.round((waktuRef.current/25)*150);
        const newYou=skorRef.current.you+p;
        setSkor(s=>{const ns={...s,you:newYou};skorRef.current=ns;return ns;});
        setHasil("you");
        sendMove({type:"ronde_selesai",ronde:idx,score:newYou});
        setTimeout(()=>lanjutKe(idx+1),1600);
      }
    }
  };
  const hapus=i=>{
    if(hasil)return;
    const item=disusun[i];
    setDisusun(d=>d.filter((_,j)=>j!==i));
    setBank(b=>[...b,item]);
  };

  useEffect(()=>{if(selesai)sendFinished(skorRef.current.you);},[selesai]);

  if(selesai){const w=skor.you===skor.op?"Seri!":skor.you>skor.op?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.you>=skor.op?P.p2:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name={namaSaya}val={skor.you}color={P.gold}win={skor.you>=skor.op}/><ScorePill name={lawan.nama}val={skor.op}color={P.p2}win={skor.op>=skor.you}/></div>}/>;}
  const ratio=waktu/25;
  return(<div style={{padding:"18px 20px 26px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center"}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><div style={{display:"flex",alignItems:"center",gap:12}}><Avatar nama={namaSaya}size={32}ring={P.gold}/><div style={{position:"relative",display:"grid",placeItems:"center"}}><TimerRing ratio={ratio}danger={ratio<.3}size={42}/><div style={{position:"absolute",fontWeight:800,fontSize:13,color:ratio<.3?P.red:P.cream}}>{Math.ceil(waktu)}</div></div><Avatar nama={lawan.nama}size={32}ring={P.p2}/></div></div><div style={{display:"flex",justifyContent:"space-between",fontSize:13,fontWeight:800,margin:"10px 0"}}><span style={{color:P.gold}}>{namaSaya}: {skor.you}</span><span style={{color:P.p2}}>{lawan.nama}: {skor.op}</span></div><div style={{marginTop:4,padding:"8px 12px",borderRadius:10,background:"rgba(255,255,255,0.04)",fontSize:13,fontWeight:700,color:P.muted}}>{hasil?<span style={{color:hasil==="you"?P.green:hasil==="op"?P.red:P.muted}}>{hasil==="you"?"Kamu berhasil duluan! 🎉":hasil==="op"?`${lawan.nama} lebih cepat…`:"Ronde seri"}</span>:<span>{lawan.nama} sedang menyusun…</span>}</div><div style={{marginTop:14,fontSize:12,fontWeight:700,color:P.p2,letterSpacing:1}}>{a.ref}</div><div style={{marginTop:8,marginBottom:8}}><div style={{fontSize:12,color:P.muted,marginBottom:6}}>Susunanmu:</div><WordArea kata={disusun.map(x=>x.kata)}onKlik={hapus}disabled={!!hasil}accent={P.p2}/></div><div><div style={{fontSize:12,color:P.muted,marginBottom:6}}>Bank Kata:</div><WordArea kata={bank.map(x=>x.kata)}onKlik={tambah}disabled={!!hasil}accent={P.muted}/></div></div>);
}

/* ════════════════════════════════════════════════════════════
   GAME 3: TEBAK TOKOH
════════════════════════════════════════════════════════════ */
const TEBAK_POIN=[400,280,180,100];

function TebakSolo({onExit}){
  const [tokoh]=useState(()=>siapkanTokoh(8));const [idx,setIdx]=useState(0);const [skor,setSkor]=useState(0);const [benarTotal,setBenarTotal]=useState(0);
  const [clueIdx,setClueIdx]=useState(0);const [pilih,setPilih]=useState(null);const [selesai,setSelesai]=useState(false);
  const t=tokoh[idx];
  const lanjut=useCallback(()=>{if(idx+1>=tokoh.length)setSelesai(true);else{setIdx(i=>i+1);setClueIdx(0);setPilih(null);}},[idx,tokoh.length]);
  const jawab=i=>{if(pilih!==null)return;setPilih(i);if(t.opsi[i]===t.jawaban){const p=TEBAK_POIN[Math.min(clueIdx,TEBAK_POIN.length-1)];setSkor(s=>s+p);setBenarTotal(b=>b+1);}setTimeout(lanjut,1600);};
  if(selesai)return<Hasil judul="Selesai! 🔍"skor={skor}accent={P.purple}onExit={onExit}baris={[{label:"Tokoh tertebak",val:`${benarTotal}/${tokoh.length}`},{label:"Akurasi",val:`${Math.round(benarTotal/tokoh.length*100)}%`},{label:"Total poin",val:skor.toLocaleString()}]}/>;
  const maxPoin=TEBAK_POIN[Math.min(clueIdx,TEBAK_POIN.length-1)];
  return(<div style={{padding:"18px 20px 28px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center"}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><Pill color={P.purple}label={`${skor} pts`}/></div><div key={idx}className="gf-pop"style={{marginTop:20,padding:"20px",borderRadius:20,background:"rgba(167,139,250,0.08)",border:"1px solid rgba(167,139,250,0.2)"}}><div style={{fontSize:12,fontWeight:700,color:P.purple,letterSpacing:1,textTransform:"uppercase"}}>Siapa Aku? · Tokoh {idx+1}/{tokoh.length}</div><div style={{marginTop:12,display:"grid",gap:8}}>{t.clues.slice(0,clueIdx+1).map((c,i)=><div key={i}style={{display:"flex",gap:9,alignItems:"flex-start"}}><span style={{color:P.purple,fontWeight:800,flexShrink:0}}>#{i+1}</span><span style={{fontWeight:600,fontSize:15,color:P.cream,lineHeight:1.4}}>{c}</span></div>)}</div>{clueIdx<t.clues.length-1&&pilih===null&&<button onClick={()=>setClueIdx(i=>i+1)}className="gf-btn"style={{marginTop:14,width:"100%",padding:"10px",borderRadius:12,border:`1px solid ${P.purple}55`,background:`${P.purple}15`,color:P.purple,fontWeight:700,fontSize:13}}>Buka Clue Berikutnya (-{TEBAK_POIN[Math.min(clueIdx,TEBAK_POIN.length-2)]-TEBAK_POIN[Math.min(clueIdx+1,TEBAK_POIN.length-1)]} poin)</button>}</div><div style={{marginTop:16}}><div style={{fontSize:12,fontWeight:700,color:P.muted,marginBottom:8}}>Siapa tokoh ini? (Nilai maks: <span style={{color:P.purple}}>{maxPoin}</span>)</div><div style={{display:"grid",gap:9}}>{t.opsi.map((op,i)=>{let st="idle";if(pilih!==null){st=op===t.jawaban?"benar":i===pilih?"salah":"redup";}return<OptBtn key={i}text={op}idx={i}state={st}onClick={()=>jawab(i)}disabled={pilih!==null}delay={i*.05}/>;})}</div></div></div>);
}

function TebakTatap({lawan,onExit}){
  const [tokoh]=useState(()=>siapkanTokoh(6));const [idx,setIdx]=useState(0);const [skor,setSkor]=useState({p1:0,p2:0});
  const [clueIdx,setClueIdx]=useState(0);const [buzzed,setBuzzed]=useState(null);const [lock,setLock]=useState({p1:false,p2:false});
  const [pilih,setPilih]=useState({p1:null,p2:null});const [winner,setWinner]=useState(null);const [selesai,setSelesai]=useState(false);const lockTimers=useRef({});
  const t=tokoh[idx];
  const lanjut=useCallback((w)=>{if(idx+1>=tokoh.length)setSelesai(true);else{setIdx(i=>i+1);setClueIdx(0);setBuzzed(null);setLock({p1:false,p2:false});setPilih({p1:null,p2:null});setWinner(null);}if(w)setSkor(s=>({...s,[w]:s[w]+TEBAK_POIN[Math.min(clueIdx,3)]}));},[idx,tokoh.length,clueIdx]);
  const buzz=pem=>{if(buzzed||lock[pem])return;setBuzzed(pem);};
  const jawab=(pem,i)=>{if(buzzed!==pem||pilih[pem]!==null)return;setPilih(p=>({...p,[pem]:i}));if(t.opsi[i]===t.jawaban){setWinner(pem);setTimeout(()=>lanjut(pem),1500);}else{setBuzzed(null);setLock(l=>({...l,[pem]:true}));lockTimers.current[pem]=setTimeout(()=>{setLock(l=>({...l,[pem]:false}));},5000);}};
  if(selesai){const w=skor.p1===skor.p2?"Seri!":skor.p1>skor.p2?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.p1>=skor.p2?P.p1:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name="Kamu"val={skor.p1}color={P.p1}win={skor.p1>=skor.p2}/><ScorePill name={lawan.nama}val={skor.p2}color={P.p2}win={skor.p2>=skor.p1}/></div>}/>;}
  const Panel=({pem,nama,color,flip})=>{const isBuzzed=buzzed===pem;const isLocked=lock[pem];return(<div style={{flex:1,padding:"12px 16px",display:"flex",flexDirection:"column",transform:flip?"rotate(180deg)":"none",background:winner===pem?`${color}14`:isBuzzed?`${color}0a`:"transparent"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center",marginBottom:8}}><div style={{display:"flex",alignItems:"center",gap:7}}><Avatar nama={nama}size={24}/><span style={{fontWeight:800,fontSize:13,color}}>{nama}</span></div><span style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:17,color}}>{skor[pem]}</span></div>{!isBuzzed&&!winner&&<button onClick={()=>buzz(pem)}disabled={!!buzzed||isLocked}className="gf-btn"style={{padding:"14px",borderRadius:14,border:`2px solid ${isLocked?"rgba(255,255,255,0.1)":color}`,background:isLocked?"rgba(255,255,255,0.03)":`${color}22`,color:isLocked?P.muted:color,fontWeight:800,fontSize:17,cursor:isLocked?"default":"pointer",transition:"all .15s"}}>{isLocked?`Dikunci sementara…`:`⚡ BUZZ!`}</button>}{isBuzzed&&!winner&&<div style={{display:"grid",gap:7}}>{t.opsi.map((op,i)=>{const st=pilih[pem]===i?(op===t.jawaban?"benar":"salah"):"idle";return(<button key={i}onClick={()=>jawab(pem,i)}className="gf-btn"style={{padding:"11px",borderRadius:12,border:`1.5px solid ${st==="benar"?P.green:st==="salah"?P.red:`${color}44`}`,background:st==="benar"?`${P.green}22`:st==="salah"?`${P.red}1f`:`${color}10`,color:P.cream,fontSize:13.5,fontWeight:700,cursor:"pointer"}}>{op}</button>);})}</div>}{winner===pem&&<div className="gf-pop"style={{textAlign:"center",marginTop:6,fontWeight:800,color,fontSize:14}}>Benar! +{TEBAK_POIN[Math.min(clueIdx,3)]} poin</div>}{isLocked&&<div className="gf-shake"style={{textAlign:"center",marginTop:6,color:P.red,fontSize:12,fontWeight:700}}>Salah! Dikunci 5 dtk ✗</div>}</div>);};
  return(<div style={{display:"flex",flexDirection:"column",minHeight:560}}><Panel pem="p2"nama={lawan.nama}color={P.p2}flip/><div style={{background:"rgba(0,0,0,0.3)",padding:"8px 14px"}}><div style={{display:"flex",alignItems:"center",justifyContent:"center",gap:12,position:"relative"}}><button onClick={onExit}className="gf-btn"style={{...gBtn,padding:"4px 10px",position:"absolute",left:0}}>←</button><div style={{textAlign:"center"}}><div style={{fontSize:11,fontWeight:800,color:P.muted,letterSpacing:1}}>TOKOH {idx+1}/{tokoh.length}</div></div></div><div style={{marginTop:8,padding:"10px 12px",borderRadius:14,background:"rgba(167,139,250,0.08)",border:"1px solid rgba(167,139,250,0.2)"}}><div style={{fontSize:11,fontWeight:700,color:P.purple,marginBottom:6}}>Clue terlihat semua pemain:</div>{t.clues.slice(0,clueIdx+1).map((c,i)=><div key={i}style={{fontSize:13,color:P.cream,fontWeight:600,marginBottom:4}}>#{i+1} {c}</div>)}{clueIdx<t.clues.length-1&&!winner&&<button onClick={()=>setClueIdx(i=>i+1)}className="gf-btn"style={{marginTop:8,width:"100%",padding:"8px",borderRadius:10,border:`1px solid ${P.purple}44`,background:`${P.purple}10`,color:P.purple,fontWeight:700,fontSize:12}}>Buka Clue Berikutnya</button>}</div></div><Panel pem="p1"nama="Kamu"color={P.p1}/></div>);
}

function TebakOnline({lawan,sessionCode,onExit}){
  const namaSaya=(window.__GAME_USER__||{nama:"Kamu"}).nama;
  const [tokoh]=useState(()=>siapkanTokohSeed(6,sessionCode+":tebak"));
  const [idx,setIdx]=useState(0);
  const [skor,setSkor]=useState({you:0,op:0});
  const skorRef=useRef({you:0,op:0});
  const [clueIdx,setClueIdx]=useState(0);
  const [pilih,setPilih]=useState(null);
  const [youLock,setYouLock]=useState(false);
  const [hasil,setHasil]=useState(null);
  const [selesai,setSelesai]=useState(false);
  const resolvedRef=useRef(false);
  const t=tokoh[idx];

  useEffect(()=>{skorRef.current=skor;},[skor]);

  const lanjut=useCallback(()=>{
    if(idx+1>=tokoh.length)setSelesai(true);
    else{setIdx(i=>i+1);setClueIdx(0);setPilih(null);setYouLock(false);setHasil(null);}
  },[idx,tokoh.length]);

  const {sendMove,sendFinished}=useOnlineGame(sessionCode,(d)=>{
    if(d.user_id!==window.__GAME_USER__?.id){
      const p=d.payload||{};
      if(p.type==="answered_correct"&&!resolvedRef.current){
        resolvedRef.current=true;
        const newOp=p.score??skorRef.current.op+TEBAK_POIN[Math.min(clueIdx,3)];
        setSkor(s=>{const ns={...s,op:newOp};skorRef.current=ns;return ns;});
        setHasil("op");
        setTimeout(lanjut,1600);
      }
    }
  },(d)=>{setSkor({you:d.score_challenger,op:d.score_opponent});setSelesai(true);});

  useEffect(()=>{resolvedRef.current=false;},[idx]);

  const jawab=i=>{
    if(resolvedRef.current||pilih!==null)return;
    setPilih(i);
    if(t.opsi[i]===t.jawaban){
      resolvedRef.current=true;
      const p=TEBAK_POIN[Math.min(clueIdx,3)];
      const newYou=skorRef.current.you+p;
      setSkor(s=>{const ns={...s,you:newYou};skorRef.current=ns;return ns;});
      setHasil("you");
      sendMove({type:"answered_correct",ronde:idx,score:newYou});
      setTimeout(lanjut,1600);
    }else{
      setYouLock(true);
      sendMove({type:"answered_wrong",ronde:idx,score:skorRef.current.you});
    }
  };

  useEffect(()=>{if(selesai)sendFinished(skorRef.current.you);},[selesai]);

  if(selesai){const w=skor.you===skor.op?"Seri!":skor.you>skor.op?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.you>=skor.op?P.purple:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name={namaSaya}val={skor.you}color={P.gold}win={skor.you>=skor.op}/><ScorePill name={lawan.nama}val={skor.op}color={P.p2}win={skor.op>=skor.you}/></div>}/>;}
  return(<div style={{padding:"18px 20px 26px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center"}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><div style={{display:"flex",gap:10}}><span style={{fontWeight:800,fontSize:15,color:P.gold}}>{skor.you}</span><span style={{color:P.muted}}>vs</span><span style={{fontWeight:800,fontSize:15,color:P.p2}}>{skor.op}</span></div></div><div key={idx}style={{marginTop:16,padding:"16px",borderRadius:18,background:"rgba(167,139,250,0.08)",border:"1px solid rgba(167,139,250,0.2)"}}><div style={{fontSize:12,fontWeight:700,color:P.purple,letterSpacing:1,marginBottom:10}}>SIAPA AKU? · Tokoh {idx+1}/{tokoh.length}</div>{t.clues.slice(0,clueIdx+1).map((c,i)=><div key={i}style={{display:"flex",gap:8,marginBottom:7}}><span style={{color:P.purple,fontWeight:800}}>#{i+1}</span><span style={{fontSize:15,fontWeight:600,color:P.cream,lineHeight:1.4}}>{c}</span></div>)}{clueIdx<t.clues.length-1&&!hasil&&<button onClick={()=>setClueIdx(i=>i+1)}className="gf-btn"style={{marginTop:10,width:"100%",padding:"9px",borderRadius:12,border:`1px solid ${P.purple}44`,background:`${P.purple}12`,color:P.purple,fontWeight:700,fontSize:13}}>Buka Clue Berikutnya</button>}</div><div style={{marginTop:12,padding:"8px 12px",borderRadius:10,background:"rgba(255,255,255,0.04)",fontSize:13,fontWeight:700}}>{hasil?<span style={{color:hasil==="you"?P.green:P.red}}>{hasil==="you"?"Kamu benar duluan! 🎉":hasil==="op"?`${lawan.nama} lebih cepat…`:"Ronde seri"}</span>:<span style={{color:P.muted}}>{lawan.nama} sedang menebak…</span>}</div><div style={{display:"grid",gap:9,marginTop:14}}>{t.opsi.map((op,i)=>{let st="idle";if(hasil||youLock){st=op===t.jawaban?"benar":i===pilih?"salah":"redup";}else if(i===pilih)st="salah";return<OptBtn key={i}text={op}idx={i}state={st}onClick={()=>jawab(i)}disabled={!!hasil||youLock||pilih!==null}delay={i*.04}/>;})}</div>{youLock&&!hasil&&<div className="gf-shake"style={{textAlign:"center",marginTop:8,color:P.red,fontSize:13,fontWeight:700}}>Jawaban salah ✗</div>}</div>);
}

/* ════════════════════════════════════════════════════════════
   GAME 4: MEMORY MATCH
════════════════════════════════════════════════════════════ */
function KartuView({kartu,terbuka,matched,onClick,disabled}){const show=terbuka||matched;return(<div onClick={disabled||matched?undefined:onClick}className="gf-card-wrap"style={{cursor:disabled||matched?"default":"pointer",height:80}}><div className={`gf-card-inner${show?" flipped":""}`}style={{height:"100%"}}><div className="gf-card-face"style={{background:"rgba(255,255,255,0.06)",border:"1.5px solid rgba(255,255,255,0.12)"}}><span style={{fontSize:22}}>✦</span></div><div className="gf-card-face gf-card-back"style={{background:matched?"rgba(74,222,128,0.15)":"rgba(37,99,235,0.15)",border:`1.5px solid ${matched?P.green:"rgba(99,102,241,0.4)"}`}}><span style={{fontSize:11.5,fontWeight:700,color:matched?P.green:P.cream,lineHeight:1.3,textAlign:"center",padding:"4px"}}>{kartu.isi}</span></div></div></div>);}

function MemorySolo({onExit}){
  const [cards]=useState(()=>siapkanKartu(8));const [terbuka,setTerbuka]=useState([]);const [matched,setMatched]=useState([]);
  const [langkah,setLangkah]=useState(0);const [waktu,setWaktu]=useState(0);const [selesai,setSelesai]=useState(false);const checkRef=useRef(false);const timerRef=useRef();
  useEffect(()=>{timerRef.current=setInterval(()=>setWaktu(w=>w+1),1000);return()=>clearInterval(timerRef.current);},[]);
  useEffect(()=>{if(matched.length===cards.length&&cards.length>0){clearInterval(timerRef.current);setSelesai(true);}},[matched,cards.length]);
  const klik=i=>{if(checkRef.current||terbuka.includes(i)||matched.includes(i)||terbuka.length>=2)return;const next=[...terbuka,i];setTerbuka(next);setLangkah(l=>l+1);if(next.length===2){checkRef.current=true;const[a,b]=next;if(cards[a].pair===cards[b].pair){setMatched(m=>[...m,a,b]);setTerbuka([]);checkRef.current=false;}else{setTimeout(()=>{setTerbuka([]);checkRef.current=false;},900);}}};
  const mnt=String(Math.floor(waktu/60)).padStart(2,"0"),dtk=String(waktu%60).padStart(2,"0");
  const skor=Math.max(0,2000-langkah*20-waktu*5);
  if(selesai)return<Hasil judul="Semua Cocok! 🃏"skor={skor}accent={P.orange}onExit={onExit}baris={[{label:"Waktu",val:`${mnt}:${dtk}`},{label:"Langkah",val:langkah},{label:"Pasangan",val:`${matched.length/2}/8`}]}/>;
  return(<div style={{padding:"18px 20px 28px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center",marginBottom:14}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><div style={{display:"flex",gap:12}}><Pill color={P.orange}label={`⏱ ${mnt}:${dtk}`}/><Pill color={P.green}label={`${matched.length/2}/8 ✓`}/></div></div><div style={{display:"grid",gridTemplateColumns:"repeat(4,1fr)",gap:8}}>{cards.map((c,i)=><KartuView key={c.id}kartu={c}terbuka={terbuka.includes(i)}matched={matched.includes(i)}onClick={()=>klik(i)}disabled={terbuka.length===2&&!terbuka.includes(i)}/>)}</div><div style={{marginTop:12,textAlign:"center",color:P.muted,fontSize:13,fontWeight:600}}>{langkah} langkah · Skor estimasi: {Math.max(0,2000-langkah*20-waktu*5)}</div></div>);
}

function MemoryTatap({lawan,onExit}){
  const [cards]=useState(()=>siapkanKartu(8));const [terbuka,setTerbuka]=useState([]);const [matched,setMatched]=useState([]);
  const [giliranP,setGiliranP]=useState("p1");const [skor,setSkor]=useState({p1:0,p2:0});const [langkah,setLangkah]=useState(0);
  const [selesai,setSelesai]=useState(false);const checkRef=useRef(false);
  useEffect(()=>{if(matched.length===cards.length&&cards.length>0)setSelesai(true);},[matched,cards.length]);
  const klik=i=>{if(checkRef.current||terbuka.includes(i)||matched.includes(i)||terbuka.length>=2)return;const next=[...terbuka,i];setTerbuka(next);setLangkah(l=>l+1);if(next.length===2){checkRef.current=true;const[a,b]=next;if(cards[a].pair===cards[b].pair){setSkor(s=>({...s,[giliranP]:s[giliranP]+1}));setMatched(m=>[...m,a,b]);setTerbuka([]);checkRef.current=false;}else{setTimeout(()=>{setTerbuka([]);setGiliranP(g=>g==="p1"?"p2":"p1");checkRef.current=false;},900);}}};
  if(selesai){const w=skor.p1===skor.p2?"Seri!":skor.p1>skor.p2?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.p1>=skor.p2?P.p1:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name="Kamu"val={skor.p1}color={P.p1}win={skor.p1>=skor.p2}/><ScorePill name={lawan.nama}val={skor.p2}color={P.p2}win={skor.p2>=skor.p1}/></div>}/>;}
  return(<div style={{padding:"18px 20px 28px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center",marginBottom:10}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><div style={{fontSize:12,fontWeight:800,color:P.muted,letterSpacing:1}}>MEMORY MATCH</div></div><div style={{display:"flex",justifyContent:"space-between",marginBottom:12,padding:"10px 14px",borderRadius:14,background:"rgba(255,255,255,0.04)",border:"1px solid rgba(255,255,255,0.08)"}}><div style={{display:"flex",alignItems:"center",gap:8}}><Avatar nama="Kamu"size={28}ring={giliranP==="p1"?P.orange:undefined}/><div><div style={{fontWeight:800,fontSize:13,color:giliranP==="p1"?P.orange:P.cream}}>Kamu {giliranP==="p1"?"← giliran":""}</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:18,color:P.orange}}>{skor.p1} pasang</div></div></div><div style={{textAlign:"right",display:"flex",alignItems:"center",gap:8}}><div><div style={{fontWeight:800,fontSize:13,color:giliranP==="p2"?P.p2:P.cream}}>{giliranP==="p2"?"giliran →":""} {lawan.nama}</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:18,color:P.p2}}>{skor.p2} pasang</div></div><Avatar nama={lawan.nama}size={28}ring={giliranP==="p2"?P.p2:undefined}/></div></div><div style={{display:"grid",gridTemplateColumns:"repeat(4,1fr)",gap:8}}>{cards.map((c,i)=><KartuView key={c.id}kartu={c}terbuka={terbuka.includes(i)}matched={matched.includes(i)}onClick={()=>klik(i)}disabled={terbuka.length===2&&!terbuka.includes(i)}/>)}</div><div style={{marginTop:10,textAlign:"center",fontSize:13,fontWeight:700,color:P.muted}}>{langkah} langkah · {matched.length/2}/8 pasang ditemukan</div></div>);
}

function MemoryOnline({lawan,sessionCode,onExit}){
  const namaSaya=(window.__GAME_USER__||{nama:"Kamu"}).nama;
  const [cards]=useState(()=>siapkanKartuSeed(8,sessionCode+":memory"));
  const [terbuka,setTerbuka]=useState([]);
  const [matched,setMatched]=useState([]);
  const [giliranKamu,setGiliranKamu]=useState(true);
  const [skor,setSkor]=useState({you:0,op:0});
  const [selesai,setSelesai]=useState(false);
  const checkRef=useRef(false);
  const matchedRef=useRef([]);
  const skorRef=useRef({you:0,op:0});

  useEffect(()=>{matchedRef.current=matched;},[matched]);
  useEffect(()=>{skorRef.current=skor;},[skor]);

  const {sendMove,sendFinished}=useOnlineGame(sessionCode,(d)=>{
    if(d.user_id!==window.__GAME_USER__?.id){
      const p=d.payload||{};
      if(p.type==="flip"){
        const[a,b]=[p.card_a,p.card_b];
        if(a===undefined||b===undefined)return;
        checkRef.current=true;
        setTerbuka([a,b]);
        setTimeout(()=>{
          if(cards[a]?.pair===cards[b]?.pair){
            setSkor(s=>{const ns={...s,op:s.op+1};skorRef.current=ns;return ns;});
            setMatched(m=>{const nm=[...m,a,b];matchedRef.current=nm;if(nm.length===cards.length)setSelesai(true);return nm;});
            setTerbuka([]);checkRef.current=false;
          }else{
            setTimeout(()=>{setTerbuka([]);setGiliranKamu(true);checkRef.current=false;},700);
          }
        },900);
      }
    }
  },(d)=>{setSkor({you:d.score_challenger,op:d.score_opponent});setSelesai(true);});

  const klik=i=>{
    if(!giliranKamu||checkRef.current||terbuka.includes(i)||matched.includes(i)||terbuka.length>=2)return;
    const next=[...terbuka,i];
    setTerbuka(next);
    if(next.length===2){
      checkRef.current=true;
      const[a,b]=next;
      sendMove({type:"flip",card_a:a,card_b:b,score:skorRef.current.you});
      if(cards[a].pair===cards[b].pair){
        const newYou=skorRef.current.you+1;
        setSkor(s=>{const ns={...s,you:newYou};skorRef.current=ns;return ns;});
        setMatched(m=>{const nm=[...m,a,b];matchedRef.current=nm;if(nm.length===cards.length)setSelesai(true);return nm;});
        setTerbuka([]);checkRef.current=false;
      }else{
        setTimeout(()=>{setTerbuka([]);setGiliranKamu(false);checkRef.current=false;},900);
      }
    }
  };

  useEffect(()=>{if(selesai)sendFinished(skorRef.current.you);},[selesai]);

  if(selesai){const w=skor.you===skor.op?"Seri!":skor.you>skor.op?"Kamu Menang! 🏆":`${lawan.nama} Menang! 🏆`;return<Hasil judul={w}skor={null}accent={skor.you>=skor.op?P.orange:P.p2}onExit={onExit}custom={<div style={{display:"flex",gap:12,justifyContent:"center",marginTop:8}}><ScorePill name={namaSaya}val={skor.you}color={P.gold}win={skor.you>=skor.op}/><ScorePill name={lawan.nama}val={skor.op}color={P.p2}win={skor.op>=skor.you}/></div>}/>;}
  return(<div style={{padding:"18px 20px 28px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",justifyContent:"space-between",alignItems:"center",marginBottom:10}}><button onClick={onExit}className="gf-btn"style={gBtn}>← Keluar</button><div style={{fontSize:12,fontWeight:800,color:giliranKamu?P.orange:P.p2}}>{giliranKamu?"Giliranmu — buka 2 kartu":"Giliran lawan…"}</div></div><div style={{display:"flex",justifyContent:"space-between",marginBottom:12,padding:"10px 14px",borderRadius:14,background:"rgba(255,255,255,0.04)"}}><div><div style={{fontWeight:700,fontSize:13,color:P.orange}}>{namaSaya}</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:22,color:P.orange}}>{skor.you}</div></div><div style={{textAlign:"right"}}><div style={{fontWeight:700,fontSize:13,color:P.p2}}>{lawan.nama}</div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:22,color:P.p2}}>{skor.op}</div></div></div><div style={{display:"grid",gridTemplateColumns:"repeat(4,1fr)",gap:8}}>{cards.map((c,i)=><KartuView key={c.id}kartu={c}terbuka={terbuka.includes(i)}matched={matched.includes(i)}onClick={()=>klik(i)}disabled={!giliranKamu||(terbuka.length===2&&!terbuka.includes(i))}/>)}</div><div style={{marginTop:10,textAlign:"center",fontSize:13,fontWeight:600,color:P.muted}}>{matched.length/2}/8 pasang ditemukan</div></div>);
}

/* ════════════════════════════════════════════════════════════
   GAME HUB + ROUTER
════════════════════════════════════════════════════════════ */
function GameCard({g,onClick}){
  const [h,setH]=useState(false);
  return(<button onClick={onClick}onMouseEnter={()=>setH(true)}onMouseLeave={()=>setH(false)}className="gf-btn gf-rise"style={{display:"flex",alignItems:"center",gap:14,padding:18,borderRadius:20,border:`1px solid ${h?g.warna:"rgba(255,255,255,0.1)"}`,background:h?`${g.warna}08`:"rgba(255,255,255,0.03)",cursor:"pointer",color:P.cream,textAlign:"left",width:"100%"}}><div style={{width:52,height:52,borderRadius:16,background:`${g.warna}22`,display:"grid",placeItems:"center",fontSize:26,flexShrink:0}}>{g.ikon}</div><div style={{flex:1}}><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:18,color:P.cream}}>{g.judul}</div><div style={{color:P.muted,fontSize:13,fontWeight:600,marginTop:2}}>{g.desc}</div></div><span style={{color:g.warna,fontSize:20}}>›</span></button>);
}

function Hub({onGame,onLeaderboard}){
  return(<div style={{padding:"26px 20px 32px",maxWidth:460,margin:"0 auto"}}><div style={{display:"flex",alignItems:"center",gap:12,marginBottom:28}}><div style={{width:46,height:46,borderRadius:16,background:`linear-gradient(135deg,${P.gold},#E8902F)`,display:"grid",placeItems:"center",boxShadow:`0 8px 24px rgba(245,196,81,0.35)`,flexShrink:0}}><span style={{fontSize:22}}>🎮</span></div><div><div style={{fontFamily:"'Bricolage Grotesque',sans-serif",fontWeight:800,fontSize:22,color:P.cream,letterSpacing:-0.4}}>Game Rohani</div><div style={{fontSize:13,color:P.muted,fontWeight:600}}>4 mini-game · Asah firman</div></div></div><div style={{display:"grid",gap:12}}>{GAME_DEFS.map((g,i)=><GameCard key={g.id}g={g}onClick={()=>onGame(g)}/>)}</div><button onClick={onLeaderboard}className="gf-btn"style={{width:"100%",marginTop:18,padding:"14px",borderRadius:18,border:`1px solid ${P.gold}44`,background:`${P.gold}0d`,color:P.gold,fontWeight:800,fontSize:15,cursor:"pointer",display:"flex",alignItems:"center",justifyContent:"center",gap:10}}><span>🏆</span> Papan Peringkat Mingguan</button></div>);
}

function GameFeature(){
  const [screen,setScreen]=useState("hub");
  const [game,setGame]=useState(null);
  const [mode,setMode]=useState(null);
  const [lawan,setLawan]=useState(null);
  const [sessionCode,setSessionCode]=useState(null);
  const [notif,setNotif]=useState(null);

  const pulang=()=>{setScreen("hub");setGame(null);setMode(null);setLawan(null);setSessionCode(null);};
  const mulaiGame=g=>{setGame(g);setScreen("cara");};
  const pilihCara=m=>{setMode(m);if(m==="solo")setScreen("main");else setScreen("lawan");};
  const pilihLawan=l=>{setLawan(l);if(mode==="online")setScreen("lobi");else setScreen("main");};

  // Langganan notifikasi tantangan masuk via Pusher
  useEffect(()=>{
    const userId=window.__GAME_USER__?.id;
    if(!userId)return;
    const pusher=getPusher();
    if(!pusher)return;
    const ch=pusher.subscribe("private-game-user."+userId);
    ch.bind("challenged",(d)=>{
      setNotif(d);
    });
    return()=>{pusher.unsubscribe("private-game-user."+userId);};
  },[]);

  const terimaNotif=async()=>{
    if(!notif)return;
    try{
      const res=await apiPost("/game/respond",{session_code:notif.session_code,accept:true});
      // Set game & lawan dari data notif SEBELUM notif dikosongkan
      setGame({id:res.game_type||notif.game_type});
      setLawan({id:notif.challenger_id,nama:notif.challenger_name});
      setSessionCode(notif.session_code);
      setMode("online");
      setScreen("main");
      setNotif(null);
    }catch(e){setNotif(null);}
  };
  const tolakNotif=async()=>{
    if(!notif)return;
    try{await apiPost("/game/respond",{session_code:notif.session_code,accept:false});}catch(e){}
    setNotif(null);
  };

  const GameMain=()=>{
    if(!game&&!sessionCode)return null;
    if(mode==="solo"){if(game.id==="kuis")return<KuisSolo onExit={pulang}/>;if(game.id==="susun")return<SusunSolo onExit={pulang}/>;if(game.id==="tebak")return<TebakSolo onExit={pulang}/>;if(game.id==="memory")return<MemorySolo onExit={pulang}/>;}
    if(mode==="tatap"){if(game.id==="kuis")return<KuisTatap lawan={lawan}onExit={pulang}/>;if(game.id==="susun")return<SusunTatap lawan={lawan}onExit={pulang}/>;if(game.id==="tebak")return<TebakTatap lawan={lawan}onExit={pulang}/>;if(game.id==="memory")return<MemoryTatap lawan={lawan}onExit={pulang}/>;}
    if(mode==="online"){
      // Jika masuk dari notif (tanpa game object), tentukan game dari sessionCode
      const gType=game?.id||notif?.game_type||"kuis";
      if(gType==="kuis")return<KuisOnline lawan={lawan}sessionCode={sessionCode}onExit={pulang}/>;
      if(gType==="susun")return<SusunOnline lawan={lawan}sessionCode={sessionCode}onExit={pulang}/>;
      if(gType==="tebak")return<TebakOnline lawan={lawan}sessionCode={sessionCode}onExit={pulang}/>;
      if(gType==="memory")return<MemoryOnline lawan={lawan}sessionCode={sessionCode}onExit={pulang}/>;
    }
    return null;
  };

  return(
    <div style={{minHeight:560,width:"100%",background:`radial-gradient(120% 90% at 50% -10%,${P.nightSoft} 0%,${P.night} 55%,#120D2E 100%)`,color:P.cream,fontFamily:"'Plus Jakarta Sans',sans-serif",borderRadius:24,overflow:"hidden",position:"relative"}}>
      <GStyles/>
      {screen==="hub"    &&<Hub onGame={mulaiGame}onLeaderboard={()=>setScreen("leaderboard")}/>}
      {screen==="leaderboard"&&<PapanPeringkat onBack={pulang}/>}
      {screen==="cara"   &&game&&<PilihCara game={game}onBack={pulang}onPick={pilihCara}/>}
      {screen==="lawan"  &&game&&<PilihLawan game={game}mode={mode}onBack={()=>setScreen("cara")}onPick={pilihLawan}/>}
      {screen==="lobi"   &&game&&lawan&&<LobiOnline lawan={lawan}game={game}onBack={()=>setScreen("lawan")}onMulai={(code)=>{setSessionCode(code);setScreen("main");}}onDeclined={()=>setScreen("lawan")}/>}
      {screen==="main"   &&<GameMain/>}
      {notif&&screen!=="main"&&<NotifTantangan notif={notif}onTerima={terimaNotif}onTolak={tolakNotif}/>}
    </div>
  );
}

/* ── MOUNT ──────────────────────────────────────────────────── */
const rootEl=document.getElementById("game-root");
if(rootEl)ReactDOM.createRoot(rootEl).render(<GameFeature/>);
