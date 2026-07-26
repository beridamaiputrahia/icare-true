var __defProp = Object.defineProperty;
var __defProps = Object.defineProperties;
var __getOwnPropDescs = Object.getOwnPropertyDescriptors;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};
var __spreadProps = (a, b) => __defProps(a, __getOwnPropDescs(b));
const { useState, useEffect, useRef, useCallback } = React;
const P = { night: "#1A1340", nightSoft: "#241A57", gold: "#F5C451", goldSoft: "#FFE08A", cream: "#F7F3E8", green: "#4ADE80", red: "#FF6B6B", p1: "#F5C451", p2: "#5EE0D0", muted: "#A99FD6", purple: "#A78BFA", orange: "#FF9F8E" };
const AVC = ["#F5C451", "#5EE0D0", "#FF9F8E", "#A78BFA", "#7DD3FC", "#FCA5D8", "#86EFAC", "#FDBA74"];
const gBtn = { padding: "8px 14px", borderRadius: 12, border: "1px solid rgba(255,255,255,0.14)", background: "rgba(255,255,255,0.04)", color: "#F7F3E8", fontWeight: 700, fontSize: 13, cursor: "pointer", fontFamily: "inherit" };
const BANK_SOAL = [
  { q: "Siapa yang membangun bahtera atas perintah Tuhan?", opsi: ["Musa", "Nuh", "Abraham", "Yusuf"], benar: 1 },
  { q: "Di kota manakah Yesus dilahirkan?", opsi: ["Nazaret", "Yerusalem", "Betlehem", "Kapernaum"], benar: 2 },
  { q: "Berapa jumlah murid Yesus?", opsi: ["10", "12", "7", "40"], benar: 1 },
  { q: "Siapa yang menerima Sepuluh Perintah Allah di Gunung Sinai?", opsi: ["Harun", "Yosua", "Musa", "Daud"], benar: 2 },
  { q: "Kitab pertama dalam Alkitab adalah?", opsi: ["Keluaran", "Kejadian", "Mazmur", "Yohanes"], benar: 1 },
  { q: "Siapa yang mengalahkan raksasa Goliat?", opsi: ["Saul", "Simson", "Daud", "Yonatan"], benar: 2 },
  { q: "Pada hari ke berapa Tuhan beristirahat setelah menciptakan dunia?", opsi: ["Keenam", "Ketujuh", "Kelima", "Kedelapan"], benar: 1 },
  { q: "Siapa nabi yang ditelan ikan besar?", opsi: ["Yesaya", "Yunus", "Elia", "Daniel"], benar: 1 },
  { q: "Siapa ibu Yesus?", opsi: ["Marta", "Maria", "Elisabet", "Hana"], benar: 1 },
  { q: "Mukjizat pertama Yesus mengubah air menjadi?", opsi: ["Roti", "Madu", "Anggur", "Minyak"], benar: 2 },
  { q: "Siapa yang menyangkal Yesus tiga kali?", opsi: ["Yudas", "Tomas", "Yohanes", "Petrus"], benar: 3 },
  { q: "Taman tempat Adam dan Hawa tinggal disebut?", opsi: ["Getsemani", "Eden", "Sinai", "Galilea"], benar: 1 },
  { q: "Siapa yang membaptis Yesus di Sungai Yordan?", opsi: ["Petrus", "Andreas", "Yohanes Pembaptis", "Elia"], benar: 2 },
  { q: "Berapa lama bangsa Israel mengembara di padang gurun?", opsi: ["7 tahun", "40 tahun", "12 tahun", "70 tahun"], benar: 1 },
  { q: "Siapa yang menjual Yusuf kepada para pedagang?", opsi: ["Ayahnya", "Orang Mesir", "Saudara-saudaranya", "Firaun"], benar: 2 },
  { q: "Kitab terakhir dalam Alkitab adalah?", opsi: ["Maleakhi", "Wahyu", "Yudas", "Kisah Para Rasul"], benar: 1 },
  { q: "Raja Israel yang terkenal bijaksana dan membangun Bait Suci pertama?", opsi: ["Daud", "Saul", "Salomo", "Hizkia"], benar: 2 },
  { q: "Roh Kudus turun atas para murid pada hari?", opsi: ["Paskah", "Pentakosta", "Natal", "Sabat"], benar: 1 },
  { q: "Siapa yang berjalan di atas air lalu mulai tenggelam?", opsi: ["Yohanes", "Yakobus", "Petrus", "Andreas"], benar: 2 },
  { q: "Berapa banyak kitab dalam Perjanjian Baru?", opsi: ["39", "27", "66", "12"], benar: 1 },
  { q: "Ratu yang menyelamatkan bangsanya, namanya menjadi judul kitab?", opsi: ["Rut", "Debora", "Ester", "Hana"], benar: 2 },
  { q: "Siapa yang dibangkitkan Yesus dari kematian setelah empat hari?", opsi: ["Lazarus", "Nikodemus", "Bartimeus", "Yairus"], benar: 0 },
  { q: "Makanan apa yang Tuhan turunkan dari langit di padang gurun?", opsi: ["Roti", "Buah ara", "Manna", "Madu"], benar: 2 },
  { q: "Rasul yang dulu menganiaya orang Kristen lalu menulis banyak surat?", opsi: ["Petrus", "Paulus", "Barnabas", "Lukas"], benar: 1 },
  { q: "Sungai tempat bayi Musa dihanyutkan?", opsi: ["Yordan", "Nil", "Efrat", "Tigris"], benar: 1 }
];
const BANK_AYAT = [
  { ref: "Yohanes 3:16", teks: "Karena begitu besar kasih Allah akan dunia ini sehingga Ia telah mengaruniakan Anak-Nya yang tunggal" },
  { ref: "Mazmur 23:1", teks: "Tuhan adalah gembalaku takkan kekurangan aku" },
  { ref: "Filipi 4:13", teks: "Segala perkara dapat kutanggung di dalam Dia yang memberi kekuatan kepadaku" },
  { ref: "Yosua 1:9", teks: "Kuatkan dan teguhkanlah hatimu sebab Tuhan Allahmu menyertai engkau" },
  { ref: "Amsal 3:5", teks: "Percayalah kepada Tuhan dengan segenap hatimu dan janganlah bersandar kepada pengertianmu sendiri" },
  { ref: "Roma 8:28", teks: "Kita tahu sekarang bahwa Allah turut bekerja dalam segala sesuatu untuk mendatangkan kebaikan" },
  { ref: "Mazmur 46:1", teks: "Allah itu bagi kita tempat perlindungan dan kekuatan sebagai penolong dalam kesesakan" },
  { ref: "Yesaya 40:31", teks: "Orang yang menanti Tuhan mendapat kekuatan baru mereka seperti rajawali yang naik terbang" },
  { ref: "1 Yohanes 4:8", teks: "Barangsiapa tidak mengasihi ia tidak mengenal Allah sebab Allah adalah kasih" },
  { ref: "Matius 5:9", teks: "Berbahagialah orang yang membawa damai karena mereka akan disebut anak-anak Allah" },
  { ref: "Mazmur 121:2", teks: "Pertolonganku ialah dari Tuhan yang menjadikan langit dan bumi" },
  { ref: "Yeremia 29:11", teks: "Sebab Aku mengetahui rancangan yang ada pada-Ku yaitu rancangan damai sejahtera bukan kecelakaan" }
];
const BANK_TOKOH = [
  { jawaban: "Musa", clues: ["Dibesarkan di istana Mesir", "Memimpin bangsa Israel keluar dari perbudakan", "Menerima Sepuluh Perintah Allah di Gunung Sinai"], salah: ["Abraham", "Elias", "Harun"] },
  { jawaban: "Daud", clues: ["Mulanya seorang gembala domba", "Mengalahkan raksasa dengan batu dan umban", "Menulis banyak Mazmur dan menjadi raja Israel"], salah: ["Goliat", "Yonatan", "Saul"] },
  { jawaban: "Yusuf", clues: ["Anak kesayangan Yakub dengan jubah istimewa", "Dijual oleh saudara-saudaranya ke Mesir", "Menjadi perdana menteri Mesir setelah menafsirkan mimpi Firaun"], salah: ["Yakub", "Benyamin", "Ruben"] },
  { jawaban: "Daniel", clues: ["Dibuang ke Babel semasa muda", "Dimasukkan ke gua singa karena setia berdoa", "Menafsirkan mimpi dan tulisan di dinding istana raja"], salah: ["Sadrakh", "Mesakh", "Abednego"] },
  { jawaban: "Ester", clues: ["Seorang Yahudi yang menjadi ratu Persia", "Menolong bangsanya dari rencana jahat Haman", "Namanya diabadikan dalam salah satu kitab Alkitab"], salah: ["Rut", "Debora", "Hana"] },
  { jawaban: "Rut", clues: ["Wanita Moab yang setia menemani mertuanya Naomi", "Memungut jelai di ladang Boas", "Menjadi nenek moyang Daud dan Yesus"], salah: ["Orpa", "Naomi", "Ester"] },
  { jawaban: "Petrus", clues: ["Seorang nelayan di Danau Galilea", "Pernah berjalan di atas air menemui Yesus", "Menyangkal Yesus tiga kali sebelum fajar"], salah: ["Yohanes", "Andreas", "Yakobus"] },
  { jawaban: "Paulus", clues: ["Pernah menganiaya orang Kristen dengan giat", "Bertobat setelah melihat cahaya di jalan Damaskus", "Menulis lebih dari separuh surat dalam Perjanjian Baru"], salah: ["Barnabas", "Silas", "Lukas"] },
  { jawaban: "Nuh", clues: ["Hidup 950 tahun lamanya", "Membangun bahtera raksasa atas perintah Tuhan", "Menyelamatkan keluarganya dan hewan dari air bah"], salah: ["Abraham", "Lot", "Sem"] },
  { jawaban: "Yunus", clues: ["Melarikan diri ke Tarsis menghindari tugas Tuhan", "Ditelan seekor ikan besar selama tiga hari tiga malam", "Memberitakan pertobatan kepada kota Niniwe"], salah: ["Elia", "Yesaya", "Mikha"] }
];
const BANK_KARTU = [
  { a: "Nuh", b: "Membangun bahtera dari kayu gofir" },
  { a: "Musa", b: "Membelah Laut Merah dengan tongkat" },
  { a: "Daud", b: "Mengalahkan Goliat dengan batu" },
  { a: "Yusuf", b: "Jubah indah berwarna-warni" },
  { a: "Daniel", b: "Selamat dari gua singa" },
  { a: "Simson", b: "Kekuatan terletak pada rambutnya" },
  { a: "Elia", b: "Naik ke surga dengan kereta api" },
  { a: "Yunus", b: "Tiga hari dalam perut ikan" },
  { a: "Maria", b: "Ibu dari Yesus Kristus" },
  { a: "Petrus", b: "Kunci kerajaan surga" },
  { a: "Paulus", b: "Bertobat di jalan Damaskus" },
  { a: "Abraham", b: "Bapak segala bangsa" },
  { a: "Salomo", b: "Membangun Bait Suci pertama" },
  { a: "Ester", b: "Menyelamatkan bangsa Yahudi" },
  { a: "Rut", b: "Setia mengikuti mertua Naomi" },
  { a: "Yosua", b: "Memimpin Israel masuk Kanaan" }
];
function getLeaderboard() {
  const raw = window.__GAME_LEADERBOARD__;
  if (!raw || !raw.length) return [];
  return raw;
}
const GAME_DEFS = [
  { id: "kuis", ikon: "\u26A1", judul: "Kuis Adu Cepat", desc: "Trivia Alkitab \u2014 jawab tercepat", warna: P.gold },
  { id: "susun", ikon: "\u{1F4D6}", judul: "Susun Ayat", desc: "Acak kata \u2014 rangkai ayat suci", warna: P.p2 },
  { id: "tebak", ikon: "\u{1F50D}", judul: "Tebak Tokoh", desc: "Clue bertahap \u2014 siapa aku?", warna: P.purple },
  { id: "memory", ikon: "\u{1F0CF}", judul: "Memory Match", desc: "Cocokkan kartu \u2014 uji ingatan", warna: P.orange }
];
function shuffle(arr) {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = 0 | Math.random() * (i + 1);
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}
function buatRng(seed) {
  let s = 0;
  for (let i = 0; i < seed.length; i++) s = (s * 31 + seed.charCodeAt(i)) >>> 0;
  return function() {
    s |= 0;
    s = s + 1831565813 | 0;
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
function siapkanSoalSeed(n, seed) {
  const rng = buatRng(seed);
  const dipilih = shuffleSeed(BANK_SOAL, rng).slice(0, n);
  return dipilih.map((s) => {
    const b = s.opsi[s.benar];
    const o = shuffleSeed(s.opsi, rng);
    return { q: s.q, opsi: o, benar: o.indexOf(b) };
  });
}
function siapkanAyatSeed(n, seed) {
  const rng = buatRng(seed);
  const dipilih = shuffleSeed(BANK_AYAT, rng).slice(0, n);
  return dipilih.map((a) => {
    const kata = a.teks.split(" ");
    return { ref: a.ref, kata, acak: shuffleSeed([...kata], rng) };
  });
}
function siapkanTokohSeed(n, seed) {
  const rng = buatRng(seed);
  const dipilih = shuffleSeed(BANK_TOKOH, rng).slice(0, n);
  return dipilih.map((t) => {
    let op = shuffleSeed([t.jawaban, ...t.salah], rng).slice(0, 4);
    if (!op.includes(t.jawaban)) op[0] = t.jawaban;
    return __spreadProps(__spreadValues({}, t), { opsi: shuffleSeed(op, rng) });
  });
}
function siapkanKartuSeed(n = 8, seed) {
  const rng = buatRng(seed);
  const p = shuffleSeed(BANK_KARTU, rng).slice(0, n);
  return shuffleSeed([...p.map((x, i) => ({ id: i * 2, pair: i, isi: x.a })), ...p.map((x, i) => ({ id: i * 2 + 1, pair: i, isi: x.b }))], rng);
}
function inisial(n) {
  return n.split(" ").map((w) => w[0]).join("").slice(0, 2).toUpperCase();
}
function warnaDari(n) {
  let h = 0;
  for (let i = 0; i < n.length; i++) h = n.charCodeAt(i) + ((h << 5) - h);
  return AVC[Math.abs(h) % AVC.length];
}
function siapkanSoal(n) {
  return shuffle(BANK_SOAL).slice(0, n).map((s) => {
    const b = s.opsi[s.benar];
    const o = shuffle(s.opsi);
    return { q: s.q, opsi: o, benar: o.indexOf(b) };
  });
}
function siapkanAyat(n) {
  return shuffle(BANK_AYAT).slice(0, n).map((a) => {
    const kata = a.teks.split(" ");
    return { ref: a.ref, kata, acak: shuffle([...kata]) };
  });
}
function siapkanTokoh(n) {
  return shuffle(BANK_TOKOH).slice(0, n).map((t) => {
    let op = shuffle([t.jawaban, ...t.salah]).slice(0, 4);
    if (!op.includes(t.jawaban)) op[0] = t.jawaban;
    return __spreadProps(__spreadValues({}, t), { opsi: shuffle(op) });
  });
}
function siapkanKartu(n = 8) {
  const p = shuffle(BANK_KARTU).slice(0, n);
  return shuffle([...p.map((x, i) => ({ id: i * 2, pair: i, isi: x.a })), ...p.map((x, i) => ({ id: i * 2 + 1, pair: i, isi: x.b }))]);
}
function getMembers() {
  const raw = window.__GAME_MEMBERS__;
  if (!raw || !raw.length) return [];
  return raw;
}
const GStyles = () => /* @__PURE__ */ React.createElement("style", null, `
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
  `);
function Avatar({ nama, size = 44, ring }) {
  const w = warnaDari(nama);
  return /* @__PURE__ */ React.createElement("div", { style: { width: size, height: size, borderRadius: "50%", background: `linear-gradient(135deg,${w},${w}aa)`, display: "grid", placeItems: "center", color: "#1A1340", fontWeight: 800, fontSize: size * 0.36, flexShrink: 0, border: ring ? `2.5px solid ${ring}` : "none", fontFamily: "'Bricolage Grotesque',sans-serif" } }, inisial(nama));
}
function TimerRing({ ratio, danger, size = 52 }) {
  const r = size / 2 - 5, c = 2 * Math.PI * r;
  return /* @__PURE__ */ React.createElement("svg", { width: size, height: size, style: { transform: "rotate(-90deg)" } }, /* @__PURE__ */ React.createElement("circle", { cx: size / 2, cy: size / 2, r, fill: "none", stroke: "rgba(255,255,255,0.12)", strokeWidth: "5" }), /* @__PURE__ */ React.createElement("circle", { cx: size / 2, cy: size / 2, r, fill: "none", stroke: danger ? P.red : P.gold, strokeWidth: "5", strokeLinecap: "round", strokeDasharray: c, strokeDashoffset: c * (1 - ratio), style: { transition: "stroke-dashoffset .25s linear,stroke .3s ease" } }));
}
function TopBar({ onBack, title, subtitle }) {
  return /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 12, marginBottom: 4 } }, /* @__PURE__ */ React.createElement("button", { onClick: onBack, className: "gf-btn", style: __spreadProps(__spreadValues({}, gBtn), { padding: "8px 12px" }) }, "\u2190 Kembali"), /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 19, color: P.cream } }, title), subtitle && /* @__PURE__ */ React.createElement("div", { style: { color: P.muted, fontSize: 12.5, fontWeight: 600 } }, subtitle)));
}
function Pill({ color, label, dim }) {
  return /* @__PURE__ */ React.createElement("span", { style: { padding: "5px 12px", borderRadius: 99, background: `${color}1f`, border: `1px solid ${color}55`, color: dim ? P.muted : color, fontWeight: 800, fontSize: 13 } }, label);
}
function OptBtn({ text, idx, state, onClick, disabled, delay = 0 }) {
  const map = { idle: { bg: "rgba(255,255,255,0.05)", bd: "rgba(255,255,255,0.12)", fg: P.cream, badge: "rgba(255,255,255,0.1)" }, benar: { bg: `${P.green}22`, bd: P.green, fg: "#D9FBE6", badge: P.green }, salah: { bg: `${P.red}1f`, bd: P.red, fg: "#FFE0E0", badge: P.red }, redup: { bg: "rgba(255,255,255,0.03)", bd: "rgba(255,255,255,0.06)", fg: "rgba(247,243,232,0.35)", badge: "rgba(255,255,255,0.05)" } };
  const s = map[state] || map.idle;
  const L = ["A", "B", "C", "D"][idx];
  return /* @__PURE__ */ React.createElement("button", { onClick, disabled, className: "gf-btn gf-rise", style: { display: "flex", alignItems: "center", gap: 13, padding: "14px 16px", borderRadius: 16, border: `1.5px solid ${s.bd}`, background: s.bg, color: s.fg, cursor: disabled ? "default" : "pointer", fontWeight: 700, fontSize: 15, textAlign: "left", width: "100%", animationDelay: `${delay}s` } }, /* @__PURE__ */ React.createElement("span", { style: { width: 28, height: 28, borderRadius: 9, background: s.badge, display: "grid", placeItems: "center", fontSize: 13, fontWeight: 800, flexShrink: 0, color: state === "idle" ? P.gold : "#1A1340" } }, state === "benar" ? "\u2713" : state === "salah" ? "\u2717" : L), /* @__PURE__ */ React.createElement("span", { style: { flex: 1 } }, text));
}
function ScorePill({ name, val, color, win }) {
  return /* @__PURE__ */ React.createElement("div", { style: { flex: 1, padding: "14px 12px", borderRadius: 18, background: win ? `${color}1a` : "rgba(255,255,255,0.04)", border: `1.5px solid ${win ? color : "rgba(255,255,255,0.1)"}`, maxWidth: 150, textAlign: "center" } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12.5, fontWeight: 700, color: P.muted } }, name), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 32, color, lineHeight: 1.1 } }, val), win && /* @__PURE__ */ React.createElement("div", { style: { fontSize: 18 } }, "\u{1F3C6}"));
}
function Hasil({ judul, skor, baris, custom, accent, onExit }) {
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "40px 24px", maxWidth: 460, margin: "0 auto", textAlign: "center", display: "flex", flexDirection: "column", minHeight: 480, justifyContent: "center" } }, /* @__PURE__ */ React.createElement("div", { className: "gf-pop" }, /* @__PURE__ */ React.createElement("div", { style: { width: 72, height: 72, margin: "0 auto", borderRadius: 24, background: `linear-gradient(135deg,${accent},${accent}99)`, display: "grid", placeItems: "center", boxShadow: `0 12px 40px ${accent}55` } }, /* @__PURE__ */ React.createElement("span", { style: { fontSize: 32 } }, "\u2B50")), /* @__PURE__ */ React.createElement("h1", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 26, margin: "18px 0 4px", color: P.cream } }, judul)), skor != null && /* @__PURE__ */ React.createElement("div", { className: "gf-rise", style: { animationDelay: ".1s" } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, color: P.muted, fontWeight: 700, letterSpacing: 1 } }, "SKOR AKHIR"), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 54, color: accent, lineHeight: 1, margin: "4px 0 20px" } }, skor)), custom, baris && /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 9, marginTop: 10 } }, baris.map((b, i) => /* @__PURE__ */ React.createElement("div", { key: i, style: { display: "flex", justifyContent: "space-between", padding: "12px 16px", borderRadius: 14, background: "rgba(255,255,255,0.04)", border: "1px solid rgba(255,255,255,0.08)" } }, /* @__PURE__ */ React.createElement("span", { style: { color: P.muted, fontWeight: 600, fontSize: 14 } }, b.label), /* @__PURE__ */ React.createElement("span", { style: { fontWeight: 800, fontSize: 15, color: P.cream } }, b.val)))), /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: { marginTop: 28, padding: 16, borderRadius: 16, border: "none", background: `linear-gradient(135deg,${accent},${accent}cc)`, color: "#1A1340", fontWeight: 800, fontSize: 16, cursor: "pointer", fontFamily: "'Bricolage Grotesque',sans-serif" } }, "Kembali ke Menu"));
}
function PilihCara({ game, onBack, onPick }) {
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "24px 20px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement(TopBar, { onBack, title: "Cara Bermain", subtitle: `${game.judul} \u2014 pilih mode` }), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 12, marginTop: 20 } }, [{ id: "solo", ikon: "\u{1F464}", judul: "Main Solo", desc: "Lawan waktu, kejar skor & streak" }, { id: "tatap", ikon: "\u{1F4F1}", judul: "Hadap-hadapan", desc: "Satu HP berdua, layar terbagi 2" }, { id: "online", ikon: "\u{1F310}", judul: "HP Masing-masing", desc: "Beda HP, main bareng secara online" }].map((m, i) => {
    const [h, setH] = useState(false);
    return /* @__PURE__ */ React.createElement("button", { key: m.id, onClick: () => onPick(m.id), onMouseEnter: () => setH(true), onMouseLeave: () => setH(false), className: "gf-btn gf-rise", style: { display: "flex", alignItems: "center", gap: 14, padding: 18, borderRadius: 20, border: `1px solid ${h ? game.warna : "rgba(255,255,255,0.1)"}`, background: h ? "rgba(255,255,255,0.06)" : "rgba(255,255,255,0.03)", cursor: "pointer", textAlign: "left", color: P.cream, animationDelay: `${i * 0.06}s` } }, /* @__PURE__ */ React.createElement("div", { style: { width: 50, height: 50, borderRadius: 16, background: `${game.warna}22`, display: "grid", placeItems: "center", fontSize: 22, flexShrink: 0 } }, m.ikon), /* @__PURE__ */ React.createElement("div", { style: { flex: 1 } }, /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 17 } }, m.judul), /* @__PURE__ */ React.createElement("div", { style: { color: P.muted, fontSize: 13, fontWeight: 600, marginTop: 2 } }, m.desc)), /* @__PURE__ */ React.createElement("span", { style: { color: game.warna, fontSize: 18 } }, "\u203A"));
  })));
}
function PilihLawan({ game, mode, onBack, onPick }) {
  const [cari, setCari] = useState("");
  const [members, setMembers] = useState(getMembers());
  useEffect(() => {
    let batal = false;
    const muat = () => apiGet("/game/members").then((data) => {
      if (!batal) setMembers(data);
    }).catch(() => {
    });
    const id = setInterval(muat, 15000);
    return () => {
      batal = true;
      clearInterval(id);
    };
  }, []);
  const list = members.filter((m) => m.nama.toLowerCase().includes(cari.toLowerCase())).sort((a, b) => b.online - a.online);
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "24px 20px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement(TopBar, { onBack, title: "Pilih Lawan", subtitle: mode === "online" ? "Hanya anggota online bisa ditantang" : "Pilih lawan bermain" }), /* @__PURE__ */ React.createElement("div", { style: { position: "relative", marginTop: 14, marginBottom: 12 } }, /* @__PURE__ */ React.createElement("span", { style: { position: "absolute", left: 14, top: 14, fontSize: 16 } }, "\u{1F50D}"), /* @__PURE__ */ React.createElement("input", { value: cari, onChange: (e) => setCari(e.target.value), placeholder: "Cari anggota\u2026", style: { width: "100%", boxSizing: "border-box", padding: "12px 14px 12px 40px", borderRadius: 14, border: "1px solid rgba(255,255,255,0.12)", background: "rgba(255,255,255,0.04)", color: P.cream, fontFamily: "inherit", fontWeight: 600, fontSize: 14.5, outline: "none" } })), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 8 } }, list.map((m, i) => {
    const bisa = mode !== "online" || m.online;
    return /* @__PURE__ */ React.createElement("button", { key: m.id || i, disabled: !bisa, onClick: () => bisa && onPick(m), className: "gf-btn gf-rise", style: { display: "flex", alignItems: "center", gap: 12, padding: 13, borderRadius: 16, border: "1px solid rgba(255,255,255,0.09)", background: "rgba(255,255,255,0.03)", cursor: bisa ? "pointer" : "default", color: P.cream, textAlign: "left", opacity: bisa ? 1 : 0.4, animationDelay: `${i * 0.04}s` } }, /* @__PURE__ */ React.createElement("div", { style: { position: "relative" } }, /* @__PURE__ */ React.createElement(Avatar, { nama: m.nama }), /* @__PURE__ */ React.createElement("span", { style: { position: "absolute", right: -1, bottom: -1, width: 11, height: 11, borderRadius: 99, background: m.online ? P.green : "#6B6391", border: `2px solid ${P.night}` } })), /* @__PURE__ */ React.createElement("div", { style: { flex: 1 } }, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 800, fontSize: 15 } }, m.nama), /* @__PURE__ */ React.createElement("div", { style: { color: P.muted, fontSize: 12, fontWeight: 600 } }, m.online ? "Online" : "Offline", " \xB7 ", m.menang || 0, "M/", m.kalah || 0, "K")), bisa && /* @__PURE__ */ React.createElement("span", { style: { fontSize: 12, fontWeight: 800, color: "#1A1340", background: game.warna, padding: "6px 14px", borderRadius: 99 } }, "Pilih"));
  }), !list.length && /* @__PURE__ */ React.createElement("div", { style: { textAlign: "center", color: P.muted, fontWeight: 600, padding: 30 } }, "Tidak ada anggota ditemukan.")));
}
function getPusher() {
  var _a;
  if (window.__PUSHER_INSTANCE__) return window.__PUSHER_INSTANCE__;
  const cfg = window.__PUSHER_CONFIG__ || {};
  if (!cfg.key) {
    console.error("[Game] PUSHER_APP_KEY tidak terkonfigurasi — mode online tidak akan realtime.");
    return null;
  }
  const p = new window.Pusher(cfg.key, {
    cluster: cfg.cluster || "ap1",
    authEndpoint: "/broadcasting/auth",
    auth: { headers: { "X-CSRF-TOKEN": ((_a = document.querySelector('meta[name="csrf-token"]')) == null ? void 0 : _a.content) || "" } }
  });
  p.connection.bind("error", (e) => console.error("[Game] Pusher connection error:", e));
  p.connection.bind("state_change", (s) => console.log("[Game] Pusher state:", s.previous, "→", s.current));
  window.__PUSHER_INSTANCE__ = p;
  return window.__PUSHER_INSTANCE__;
}
async function apiPost(url, data) {
  var _a;
  const token = ((_a = document.querySelector('meta[name="csrf-token"]')) == null ? void 0 : _a.content) || "";
  const r = await fetch(url, { method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": token, "Accept": "application/json" }, body: JSON.stringify(data) });
  if (!r.ok) throw new Error(await r.text());
  return r.json();
}
async function apiGet(url) {
  const r = await fetch(url, { headers: { "Accept": "application/json" } });
  if (!r.ok) throw new Error(await r.text());
  return r.json();
}
function LobiOnline({ lawan, game, sessionCode, onBack, onMulai, onDeclined }) {
  const [fase, setFase] = useState(sessionCode ? "tunggu" : "kirim");
  const [kode, setKode] = useState(sessionCode || null);
  const pusherRef = useRef(null);
  const channelRef = useRef(null);
  const pollRef = useRef(null);
  useEffect(() => {
    let cancelled = false;
    const pusher = getPusher();
    async function kirim() {
      try {
        let kodeAktif = sessionCode;
        if (!kodeAktif) {
          const res = await apiPost("/game/challenge", { opponent_id: lawan.id, game_type: game.id });
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
              setTimeout(() => onMulai(kodeAktif), 1e3);
            }
          });
          ch.bind("move", (d) => {
            var _a;
            if (((_a = d == null ? void 0 : d.payload) == null ? void 0 : _a.type) === "declined" && !cancelled) {
              setFase("ditolak");
              setTimeout(onDeclined, 2e3);
            }
          });
        }
        pollRef.current = setInterval(async () => {
          if (cancelled) return;
          try {
            const s = await apiGet("/game/session/" + kodeAktif);
            if (cancelled) return;
            if (s.status === "active") {
              clearInterval(pollRef.current);
              setFase("diterima");
              setTimeout(() => onMulai(kodeAktif), 800);
            } else if (s.status === "declined") {
              clearInterval(pollRef.current);
              setFase("ditolak");
              setTimeout(onDeclined, 2e3);
            }
          } catch (e) {
          }
        }, 2500);
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
  const teks = { kirim: "Mengirim tantangan\u2026", tunggu: `Menunggu ${lawan.nama} menerima\u2026`, diterima: "Tantangan diterima! \u{1F389}", ditolak: "Tantangan ditolak.", error: "Gagal mengirim tantangan." };
  const warna = { diterima: P.green, ditolak: P.red, error: P.red };
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "24px 20px", minHeight: 400, maxWidth: 460, margin: "0 auto", display: "flex", flexDirection: "column" } }, /* @__PURE__ */ React.createElement(TopBar, { onBack, title: "Menghubungkan\u2026" }), /* @__PURE__ */ React.createElement("div", { style: { flex: 1, display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", gap: 24 } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 20 } }, /* @__PURE__ */ React.createElement("div", { style: { textAlign: "center" } }, /* @__PURE__ */ React.createElement(Avatar, { nama: (window.__GAME_USER__ || { nama: "Kamu" }).nama, size: 60, ring: P.gold }), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 8, fontWeight: 800, fontSize: 13, color: P.cream } }, "Kamu")), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 20, color: P.muted } }, "VS"), /* @__PURE__ */ React.createElement("div", { style: { textAlign: "center" } }, /* @__PURE__ */ React.createElement(Avatar, { nama: lawan.nama, size: 60, ring: fase === "diterima" ? P.green : game.warna }), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 8, fontWeight: 800, fontSize: 13, color: P.cream } }, lawan.nama))), /* @__PURE__ */ React.createElement("div", { key: fase, className: "gf-pop", style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 18, color: warna[fase] || P.cream } }, teks[fase]), (fase === "kirim" || fase === "tunggu") && /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 6 } }, [0, 1, 2].map((i) => /* @__PURE__ */ React.createElement("div", { key: i, style: { width: 8, height: 8, borderRadius: 99, background: P.gold, animation: `gf-blink 1.2s ${i * 0.4}s ease infinite` } }))), kode && fase === "tunggu" && /* @__PURE__ */ React.createElement("div", { style: { fontSize: 11, color: P.muted, fontWeight: 600 } }, "Kode sesi: ", kode)));
}
function NotifTantangan({ notif, onTerima, onTolak }) {
  return /* @__PURE__ */ React.createElement("div", { className: "gf-pop", style: { position: "fixed", bottom: 90, left: "50%", transform: "translateX(-50%)", width: "calc(100% - 32px)", maxWidth: 440, zIndex: 9999, padding: "16px 18px", borderRadius: 20, background: "#241A57", border: `1.5px solid ${P.gold}`, boxShadow: "0 8px 32px rgba(0,0,0,0.5)" } }, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 800, fontSize: 14, color: P.gold, marginBottom: 4 } }, "\u{1F3AE} Tantangan Masuk!"), /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 700, fontSize: 14, color: P.cream, marginBottom: 12 } }, /* @__PURE__ */ React.createElement("b", null, notif.challenger_name), " mengajakmu main ", /* @__PURE__ */ React.createElement("b", null, notif.game_type)), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 10 } }, /* @__PURE__ */ React.createElement("button", { onClick: onTerima, className: "gf-btn", style: { flex: 1, padding: "10px", borderRadius: 12, border: "none", background: P.green, color: "#1A1340", fontWeight: 800, fontSize: 14, cursor: "pointer" } }, "\u2713 Terima"), /* @__PURE__ */ React.createElement("button", { onClick: onTolak, className: "gf-btn", style: { flex: 1, padding: "10px", borderRadius: 12, border: `1px solid ${P.red}`, background: "transparent", color: P.red, fontWeight: 800, fontSize: 14, cursor: "pointer" } }, "\u2717 Tolak")));
}
function PapanPeringkat({ onBack }) {
  const [tab, setTab] = useState("semua");
  const tabs = [{ id: "semua", label: "Semua" }, { id: "kuis", label: "\u26A1 Kuis" }, { id: "susun", label: "\u{1F4D6} Susun" }, { id: "tebak", label: "\u{1F50D} Tebak" }, { id: "memory", label: "\u{1F0CF} Memory" }];
  const sorted = [...getLeaderboard()].sort((a, b) => (tab === "semua" ? b.poin : b.detail[tab]) - (tab === "semua" ? a.poin : a.detail[tab]));
  const medals = ["\u{1F947}", "\u{1F948}", "\u{1F949}"];
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "20px 20px 32px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement(TopBar, { onBack, title: "Papan Peringkat", subtitle: "Minggu ini \xB7 Reset tiap Senin" }), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 6, marginTop: 16, overflowX: "auto", paddingBottom: 4 } }, tabs.map((t) => /* @__PURE__ */ React.createElement("button", { key: t.id, onClick: () => setTab(t.id), className: "gf-btn", style: { padding: "7px 14px", borderRadius: 99, flexShrink: 0, border: `1px solid ${tab === t.id ? P.gold : "rgba(255,255,255,0.12)"}`, background: tab === t.id ? `${P.gold}22` : "rgba(255,255,255,0.04)", color: tab === t.id ? P.gold : P.muted, fontWeight: 700, fontSize: 13 } }, t.label))), sorted.length === 0 ? /* @__PURE__ */ React.createElement("div", { style: { textAlign: "center", padding: "40px 20px", color: P.muted, fontSize: 14 } }, "Belum ada yang bermain minggu ini") : /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 9, marginTop: 16 } }, sorted.map((m, i) => {
    const poin = tab === "semua" ? m.poin : m.detail[tab];
    return /* @__PURE__ */ React.createElement("div", { key: i, className: "gf-rise", style: { display: "flex", alignItems: "center", gap: 12, padding: "13px 16px", borderRadius: 16, border: `1px solid ${i < 3 ? "rgba(245,196,81,0.3)" : "rgba(255,255,255,0.08)"}`, background: i < 3 ? `${P.gold}0a` : "rgba(255,255,255,0.03)", animationDelay: `${i * 0.04}s` } }, /* @__PURE__ */ React.createElement("div", { style: { width: 26, textAlign: "center", fontSize: 20, fontWeight: 800, flexShrink: 0 } }, medals[i] || /* @__PURE__ */ React.createElement("span", { style: { fontSize: 14, color: P.muted, fontWeight: 800 } }, "#", i + 1)), /* @__PURE__ */ React.createElement(Avatar, { nama: m.nama, size: 38 }), /* @__PURE__ */ React.createElement("div", { style: { flex: 1 } }, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 800, fontSize: 15, color: P.cream } }, m.nama)), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 20, color: i === 0 ? P.gold : P.cream } }, poin.toLocaleString()));
  })));
}
function optState(i, pilih, benar) {
  if (pilih === null) return "idle";
  if (i === benar) return "benar";
  if (i === pilih) return "salah";
  return "redup";
}
function KuisSolo({ onExit }) {
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
    if (idx + 1 >= soal.length) setSelesai(true);
    else {
      setIdx((i) => i + 1);
      setPilih(null);
      setWaktu(15);
    }
  }, [idx, soal.length]);
  const jawab = useCallback((i) => {
    if (pilih !== null) return;
    clearInterval(timerRef.current);
    setPilih(i);
    if (i === s.benar) {
      const p = 100 + Math.round(waktu / 15 * 100) + streak * 20;
      setPoin(p);
      setSkor((sc) => sc + p);
      setBenarTotal((b) => b + 1);
      setStreak((st) => {
        const n = st + 1;
        setBest((bs) => Math.max(bs, n));
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
      setWaktu((w) => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          setPilih(-1);
          setStreak(0);
          setPoin(0);
          setTimeout(lanjut, 1400);
          return 0;
        }
        return +(w - 0.1).toFixed(1);
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, pilih, selesai, lanjut]);
  if (selesai) return /* @__PURE__ */ React.createElement(Hasil, { judul: "Selesai! \u{1F389}", skor, accent: P.gold, onExit, baris: [{ label: "Jawaban benar", val: `${benarTotal}/10` }, { label: "Streak terbaik", val: `${best} \u{1F525}` }, { label: "Akurasi", val: `${Math.round(benarTotal / 10 * 100)}%` }] });
  const ratio = waktu / 15;
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 28px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 8 } }, /* @__PURE__ */ React.createElement(Pill, { color: P.gold, label: `${skor} pts` }), /* @__PURE__ */ React.createElement(Pill, { color: P.red, label: `${streak}\u{1F525}`, dim: streak === 0 }))), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 16 } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", fontSize: 12.5, fontWeight: 700, color: P.muted, marginBottom: 7 } }, /* @__PURE__ */ React.createElement("span", null, "Soal ", idx + 1, "/10"), /* @__PURE__ */ React.createElement("span", { style: { color: ratio < 0.3 ? P.red : P.gold } }, Math.ceil(waktu), " dtk")), /* @__PURE__ */ React.createElement("div", { style: { height: 6, background: "rgba(255,255,255,0.1)", borderRadius: 99, overflow: "hidden" } }, /* @__PURE__ */ React.createElement("div", { style: { height: "100%", width: `${ratio * 100}%`, background: ratio < 0.3 ? P.red : `linear-gradient(90deg,${P.gold},${P.goldSoft})`, transition: "width .1s linear" } }))), /* @__PURE__ */ React.createElement("div", { key: idx, className: "gf-pop", style: { marginTop: 22 } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 700, color: P.gold, letterSpacing: 1, textTransform: "uppercase" } }, "Pertanyaan"), /* @__PURE__ */ React.createElement("h2", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 22, lineHeight: 1.25, margin: "8px 0 0", color: P.cream } }, s.q)), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 10, marginTop: 20 } }, s.opsi.map((op, i) => /* @__PURE__ */ React.createElement(OptBtn, { key: i, text: op, idx: i, state: optState(i, pilih, s.benar), onClick: () => jawab(i), disabled: pilih !== null, delay: i * 0.05 }))), /* @__PURE__ */ React.createElement("div", { style: { minHeight: 30, marginTop: 12, textAlign: "center" } }, pilih !== null && /* @__PURE__ */ React.createElement("div", { className: "gf-pop", style: { fontWeight: 800, fontSize: 16, color: poin > 0 ? P.green : P.red, fontFamily: "'Bricolage Grotesque',sans-serif" } }, poin > 0 ? `+${poin} poin!` : pilih === -1 ? "Waktu habis \u23F1" : "Belum tepat")));
}
function KuisTatap({ lawan, onExit }) {
  const [soal] = useState(() => siapkanSoal(7));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({ p1: 0, p2: 0 });
  const [lock, setLock] = useState({ p1: false, p2: false });
  const [pilih, setPilih] = useState({ p1: null, p2: null });
  const [winner, setWinner] = useState(null);
  const [waktu, setWaktu] = useState(12);
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const s = soal[idx];
  const lanjut = useCallback(() => {
    if (idx + 1 >= soal.length) setSelesai(true);
    else {
      setIdx((i) => i + 1);
      setLock({ p1: false, p2: false });
      setPilih({ p1: null, p2: null });
      setWinner(null);
      setWaktu(12);
    }
  }, [idx, soal.length]);
  const jawab = useCallback((pem, i) => {
    if (winner || lock[pem] || pilih[pem] !== null) return;
    setPilih((p) => __spreadProps(__spreadValues({}, p), { [pem]: i }));
    if (i === s.benar) {
      clearInterval(timerRef.current);
      const p = 100 + Math.round(waktu / 12 * 50);
      setSkor((sc) => __spreadProps(__spreadValues({}, sc), { [pem]: sc[pem] + p }));
      setWinner(pem);
      setTimeout(lanjut, 1500);
    } else setLock((l) => __spreadProps(__spreadValues({}, l), { [pem]: true }));
  }, [winner, lock, pilih, s, waktu, lanjut]);
  useEffect(() => {
    if (selesai || winner) return;
    timerRef.current = setInterval(() => {
      setWaktu((w) => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          setWinner("seri");
          setTimeout(lanjut, 1500);
          return 0;
        }
        return +(w - 0.1).toFixed(1);
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, winner, selesai, lanjut]);
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.p1 >= skor.p2 ? P.p1 : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: "Kamu", val: skor.p1, color: P.p1, win: skor.p1 >= skor.p2 }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.p2, color: P.p2, win: skor.p2 >= skor.p1 })) });
  }
  const ratio = waktu / 12;
  const Panel = ({ pem, nama, color, flip }) => {
    const w = winner === pem;
    return /* @__PURE__ */ React.createElement("div", { style: { flex: 1, padding: "14px 16px", display: "flex", flexDirection: "column", transform: flip ? "rotate(180deg)" : "none", background: w ? `${color}14` : "transparent", transition: "background .3s" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 10 } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 8 } }, /* @__PURE__ */ React.createElement(Avatar, { nama, size: 26 }), /* @__PURE__ */ React.createElement("span", { style: { fontWeight: 800, fontSize: 13, color } }, nama)), /* @__PURE__ */ React.createElement("span", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 18, color } }, skor[pem])), /* @__PURE__ */ React.createElement("h3", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 16, lineHeight: 1.25, margin: "0 0 10px", color: P.cream } }, s.q), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gridTemplateColumns: "1fr 1fr", gap: 7, flex: 1 } }, s.opsi.map((op, i) => {
      let st = "idle";
      if (winner) {
        st = i === s.benar ? "benar" : i === pilih[pem] ? "salah" : "redup";
      } else if (pilih[pem] === i) st = "salah";
      else if (lock[pem]) st = "redup";
      return /* @__PURE__ */ React.createElement("button", { key: i, onClick: () => jawab(pem, i), disabled: !!winner || lock[pem] || pilih[pem] !== null, className: "gf-btn", style: { padding: "11px", borderRadius: 12, border: `1.5px solid ${st === "benar" ? P.green : st === "salah" ? P.red : "rgba(255,255,255,0.12)"}`, background: st === "benar" ? `${P.green}22` : st === "salah" ? `${P.red}1f` : "rgba(255,255,255,0.05)", color: P.cream, cursor: "pointer", fontSize: 13.5, fontWeight: 700, lineHeight: 1.2, minHeight: 48 } }, op);
    })), w && /* @__PURE__ */ React.createElement("div", { className: "gf-pop", style: { textAlign: "center", marginTop: 8, fontWeight: 800, color, fontSize: 14 } }, "Tercepat! \u26A1"), winner && !w && winner !== "seri" && /* @__PURE__ */ React.createElement("div", { style: { textAlign: "center", marginTop: 8, color: P.muted, fontSize: 13, fontWeight: 700 } }, "Keduluan\u2026"), winner === "seri" && /* @__PURE__ */ React.createElement("div", { style: { textAlign: "center", marginTop: 8, color: P.muted, fontSize: 13, fontWeight: 700 } }, "Waktu habis"), lock[pem] && !winner && /* @__PURE__ */ React.createElement("div", { className: "gf-shake", style: { textAlign: "center", marginTop: 8, color: P.red, fontSize: 13, fontWeight: 700 } }, "Terkunci ronde ini \u2717"));
  };
  return /* @__PURE__ */ React.createElement("div", { style: { display: "flex", flexDirection: "column", minHeight: 560 } }, /* @__PURE__ */ React.createElement(Panel, { pem: "p2", nama: lawan.nama, color: P.p2, flip: true }), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", justifyContent: "center", gap: 14, padding: "8px 16px", background: "rgba(0,0,0,0.3)", position: "relative" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: __spreadProps(__spreadValues({}, gBtn), { padding: "5px 10px", position: "absolute", left: 10 }) }, "\u2190"), /* @__PURE__ */ React.createElement("span", { style: { fontSize: 11, fontWeight: 800, color: P.muted, letterSpacing: 1 } }, "RONDE ", idx + 1, "/", soal.length), /* @__PURE__ */ React.createElement("div", { style: { position: "relative", display: "grid", placeItems: "center" } }, /* @__PURE__ */ React.createElement(TimerRing, { ratio, danger: ratio < 0.3, size: 50 }), /* @__PURE__ */ React.createElement("div", { style: { position: "absolute", fontWeight: 800, fontSize: 15, fontFamily: "'Bricolage Grotesque',sans-serif", color: ratio < 0.3 ? P.red : P.cream } }, Math.ceil(waktu))), /* @__PURE__ */ React.createElement("span", { style: { fontSize: 11, fontWeight: 800, color: P.muted, letterSpacing: 1 } }, "ADU CEPAT")), /* @__PURE__ */ React.createElement(Panel, { pem: "p1", nama: "Kamu", color: P.p1 }));
}
function useOnlineGame(sessionCode, onMove, onEnded) {
  const pusherRef = useRef(null);
  const chRef = useRef(null);
  const onMoveRef = useRef(onMove);
  const onEndedRef = useRef(onEnded);
  onMoveRef.current = onMove;
  onEndedRef.current = onEnded;
  useEffect(() => {
    if (!sessionCode) return;
    const pusher = getPusher();
    if (!pusher) return;
    const ch = pusher.subscribe("private-game-session." + sessionCode);
    chRef.current = ch;
    ch.bind("move", (d) => onMoveRef.current && onMoveRef.current(d));
    ch.bind("ended", (d) => onEndedRef.current && onEndedRef.current(d));
    return () => {
      pusher.unsubscribe("private-game-session." + sessionCode);
    };
  }, [sessionCode]);
  const sendMove = useCallback(async (payload) => {
    if (!sessionCode) return;
    try {
      await apiPost("/game/move", { session_code: sessionCode, payload });
    } catch (e) {
      console.error("[Game] Gagal kirim move:", e);
    }
  }, [sessionCode]);
  const sendFinished = useCallback(async (score) => {
    if (!sessionCode) return;
    try {
      await apiPost("/game/move", { session_code: sessionCode, payload: { finished: true, score } });
    } catch (e) {
      console.error("[Game] Gagal kirim finished:", e);
    }
  }, [sessionCode]);
  return { sendMove, sendFinished };
}
function SkorBarOnline({ namaSaya, namaLawan, skorSaya, skorLawan, tengah }) {
  return /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", justifyContent: "space-between", marginTop: 14, gap: 10 } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 9, flex: 1 } }, /* @__PURE__ */ React.createElement(Avatar, { nama: namaSaya, size: 38, ring: P.gold }), /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 800, fontSize: 13, color: P.gold } }, namaSaya), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 20, color: P.cream } }, skorSaya))), tengah, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 9, flex: 1, justifyContent: "flex-end" } }, /* @__PURE__ */ React.createElement("div", { style: { textAlign: "right" } }, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 800, fontSize: 13, color: P.p2 } }, namaLawan), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 20, color: P.cream } }, skorLawan)), /* @__PURE__ */ React.createElement(Avatar, { nama: namaLawan, size: 38, ring: P.p2 })));
}
function KuisOnline({ lawan, sessionCode, onExit }) {
  const namaSaya = (window.__GAME_USER__ || { nama: "Kamu" }).nama;
  const [soal] = useState(() => siapkanSoalSeed(7, sessionCode + ":kuis"));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({ you: 0, op: 0 });
  const [pilih, setPilih] = useState(null);
  const [youLock, setYouLock] = useState(false);
  const [opStatus, setOpStatus] = useState("thinking");
  const [hasil, setHasil] = useState(null);
  const [waktu, setWaktu] = useState(12);
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const resolvedRef = useRef(false);
  const waktuRef = useRef(12);
  const youLockRef = useRef(false);
  const s = soal[idx];
  const lanjut = useCallback(() => {
    if (idx + 1 >= soal.length) setSelesai(true);
    else {
      setIdx((i) => i + 1);
      setPilih(null);
      setYouLock(false);
      setOpStatus("thinking");
      setHasil(null);
      setWaktu(12);
    }
  }, [idx, soal.length]);
  const resolve = useCallback((w, youSkor) => {
    if (resolvedRef.current) return;
    resolvedRef.current = true;
    clearInterval(timerRef.current);
    setHasil(w);
    if (w === "you") {
      setSkor((s2) => __spreadProps(__spreadValues({}, s2), { you: s2.you + youSkor }));
    }
    setTimeout(lanjut, 1700);
  }, [lanjut]);
  const { sendMove, sendFinished } = useOnlineGame(sessionCode, (d) => {
    var _a;
    if (d.role === "opponent" || d.user_id !== ((_a = window.__GAME_USER__) == null ? void 0 : _a.id)) {
      const p = d.payload || {};
      if (p.type === "answer_correct") {
        setOpStatus("answered");
        resolve("op", 0);
        setSkor((s2) => {
          var _a2;
          return __spreadProps(__spreadValues({}, s2), { op: (_a2 = d.score_opponent) != null ? _a2 : s2.op + 100 });
        });
      } else if (p.type === "answer_wrong") {
        setOpStatus("wrong");
      }
    }
  }, (d) => {
    setSkor({ you: d.score_challenger, op: d.score_opponent });
    setSelesai(true);
  });
  const jawab = useCallback((i) => {
    if (resolvedRef.current || pilih !== null || youLockRef.current) return;
    setPilih(i);
    const poin = 100 + Math.round(waktuRef.current / 12 * 50);
    if (i === s.benar) {
      sendMove({ type: "answer_correct", ronde: idx, score: skor.you + poin });
      resolve("you", poin);
    } else {
      youLockRef.current = true;
      setYouLock(true);
      sendMove({ type: "answer_wrong", ronde: idx, score: skor.you });
    }
  }, [pilih, s, resolve, idx, skor.you, sendMove]);
  useEffect(() => {
    resolvedRef.current = false;
    youLockRef.current = false;
    waktuRef.current = 12;
  }, [idx]);
  useEffect(() => {
    if (selesai) return;
    timerRef.current = setInterval(() => {
      setWaktu((w) => {
        const n = w <= 0.1 ? 0 : +(w - 0.1).toFixed(1);
        waktuRef.current = n;
        if (n === 0 && !resolvedRef.current) resolve("seri", 0);
        return n;
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, selesai, resolve]);
  useEffect(() => {
    if (selesai) sendFinished(skor.you);
  }, [selesai]);
  if (selesai) {
    const w = skor.you === skor.op ? "Seri!" : skor.you > skor.op ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.you >= skor.op ? P.gold : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: namaSaya, val: skor.you, color: P.gold, win: skor.you >= skor.op }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.op, color: P.p2, win: skor.op >= skor.you })) });
  }
  const ratio = waktu / 12;
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 26px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement("span", { style: { fontSize: 11, fontWeight: 800, color: P.muted, letterSpacing: 1 } }, "RONDE ", idx + 1, "/", soal.length)), /* @__PURE__ */ React.createElement(SkorBarOnline, { namaSaya, namaLawan: lawan.nama, skorSaya: skor.you, skorLawan: skor.op, tengah: /* @__PURE__ */ React.createElement("div", { style: { position: "relative", display: "grid", placeItems: "center", flexShrink: 0 } }, /* @__PURE__ */ React.createElement(TimerRing, { ratio, danger: ratio < 0.3, size: 48 }), /* @__PURE__ */ React.createElement("div", { style: { position: "absolute", fontWeight: 800, fontSize: 14, color: ratio < 0.3 ? P.red : P.cream } }, Math.ceil(waktu))) }), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 10, padding: "9px 14px", borderRadius: 12, background: "rgba(255,255,255,0.04)", border: "1px solid rgba(255,255,255,0.08)", display: "flex", alignItems: "center", gap: 9, fontSize: 13, fontWeight: 700 } }, /* @__PURE__ */ React.createElement("span", { style: { width: 8, height: 8, borderRadius: 99, background: P.p2, animation: opStatus === "thinking" ? "gf-blink 1s ease infinite" : "none", display: "inline-block" } }), hasil ? /* @__PURE__ */ React.createElement("span", { style: { color: hasil === "op" ? P.p2 : P.muted } }, hasil === "op" ? `${lawan.nama} lebih cepat` : hasil === "you" ? `${lawan.nama} keduluan kamu` : "Ronde seri") : opStatus === "thinking" ? /* @__PURE__ */ React.createElement("span", { style: { color: P.muted } }, lawan.nama, " sedang menjawab\u2026") : /* @__PURE__ */ React.createElement("span", { style: { color: P.red } }, lawan.nama, " salah \u2014 peluangmu!")), /* @__PURE__ */ React.createElement("div", { key: idx, className: "gf-pop", style: { marginTop: 18 } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 700, color: P.gold, letterSpacing: 1, textTransform: "uppercase" } }, "Pertanyaan"), /* @__PURE__ */ React.createElement("h2", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 21, lineHeight: 1.25, margin: "7px 0 0", color: P.cream } }, s.q)), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 9, marginTop: 16 } }, s.opsi.map((op, i) => {
    let st = "idle";
    if (hasil) {
      st = i === s.benar ? "benar" : i === pilih ? "salah" : "redup";
    } else if (pilih === i) st = "salah";
    else if (youLock) st = "redup";
    return /* @__PURE__ */ React.createElement(OptBtn, { key: i, text: op, idx: i, state: st, onClick: () => jawab(i), disabled: hasil !== null || youLock || pilih !== null, delay: i * 0.04 });
  })), /* @__PURE__ */ React.createElement("div", { style: { minHeight: 26, marginTop: 10, textAlign: "center" } }, hasil && /* @__PURE__ */ React.createElement("div", { className: "gf-pop", style: { fontWeight: 800, fontSize: 15, color: hasil === "you" ? P.green : hasil === "op" ? P.red : P.muted } }, hasil === "you" ? "Kamu tercepat! \u26A1" : hasil === "op" ? "Keduluan lawan\u2026" : "Ronde seri"), youLock && !hasil && /* @__PURE__ */ React.createElement("div", { className: "gf-shake", style: { fontWeight: 700, fontSize: 13, color: P.red } }, "Jawabanmu salah \u2014 terkunci ronde ini \u2717")));
}
function WordArea({ kata, onKlik, disabled, accent }) {
  return /* @__PURE__ */ React.createElement("div", { style: { display: "flex", flexWrap: "wrap", gap: 7, minHeight: 48, padding: "10px 12px", borderRadius: 14, border: "1px dashed rgba(255,255,255,0.18)", background: "rgba(255,255,255,0.03)" } }, kata.map((k, i) => /* @__PURE__ */ React.createElement("button", { key: i, onClick: () => !disabled && onKlik(i), disabled, className: "gf-btn", style: { padding: "8px 14px", borderRadius: 10, border: `1px solid ${accent}55`, background: `${accent}15`, color: P.cream, fontSize: 14, fontWeight: 700, cursor: disabled ? "default" : "pointer" } }, k)), !kata.length && /* @__PURE__ */ React.createElement("span", { style: { color: P.muted, fontSize: 13, fontWeight: 600 } }, "Tap kata di bawah untuk menyusun\u2026"));
}
function SusunSolo({ onExit }) {
  const [ayat] = useState(() => siapkanAyat(5));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(0);
  const [benarTotal, setBenarTotal] = useState(0);
  const [disusun, setDisusun] = useState([]);
  const [bank, setBank] = useState([]);
  const [waktu, setWaktu] = useState(30);
  const [selesai, setSelesai] = useState(false);
  const [flash, setFlash] = useState(null);
  const [streak, setStreak] = useState(0);
  const timerRef = useRef();
  const a = ayat[idx];
  useEffect(() => {
    setBank(a.acak.map((k, i) => ({ kata: k, origIdx: i })));
    setDisusun([]);
  }, [idx]);
  const lanjut = useCallback((berhasil) => {
    clearInterval(timerRef.current);
    if (berhasil) {
      const p = 100 + Math.round(waktu / 30 * 150) + streak * 25;
      setSkor((s) => s + p);
      setBenarTotal((b) => b + 1);
      setStreak((s) => s + 1);
      setFlash({ ok: true, pesan: `+${p} poin!` });
    } else {
      setStreak(0);
      setFlash({ ok: false, pesan: "Waktu habis \u23F1" });
    }
    setTimeout(() => {
      setFlash(null);
      if (idx + 1 >= ayat.length) setSelesai(true);
      else {
        setIdx((i) => i + 1);
        setWaktu(30);
      }
    }, 1400);
  }, [waktu, streak, idx, ayat.length]);
  useEffect(() => {
    if (selesai || flash) return;
    timerRef.current = setInterval(() => {
      setWaktu((w) => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          lanjut(false);
          return 0;
        }
        return +(w - 0.1).toFixed(1);
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, selesai, flash, lanjut]);
  const tambah = (i) => {
    if (flash) return;
    const item = bank[i];
    const nd = [...disusun, item];
    setDisusun(nd);
    setBank((b) => b.filter((_, j) => j !== i));
    if (nd.length === a.kata.length) {
      const benar = nd.every((it, j) => it.kata === a.kata[j]);
      if (benar) lanjut(true);
      else setFlash({ ok: false, pesan: "Susunan belum tepat \u{1F914}" });
    }
  };
  const hapus = (i) => {
    const item = disusun[i];
    setDisusun((d) => d.filter((_, j) => j !== i));
    setBank((b) => [...b, item]);
    setFlash(null);
  };
  if (selesai) return /* @__PURE__ */ React.createElement(Hasil, { judul: "Selesai! \u{1F4D6}", skor, accent: P.p2, onExit, baris: [{ label: "Ayat tersusun", val: `${benarTotal}/5` }, { label: "Streak terbaik", val: `${Math.max(benarTotal, 0)} \u{1F525}` }, { label: "Total poin", val: skor.toLocaleString() }] });
  const ratio = waktu / 30;
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 28px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement(Pill, { color: P.p2, label: `${skor} pts` })), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 14, marginBottom: 16 } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", fontSize: 12.5, fontWeight: 700, color: P.muted, marginBottom: 6 } }, /* @__PURE__ */ React.createElement("span", null, "Ayat ", idx + 1, "/5 \xB7 ", /* @__PURE__ */ React.createElement("span", { style: { color: P.p2 } }, a.ref)), /* @__PURE__ */ React.createElement("span", { style: { color: ratio < 0.3 ? P.red : P.p2 } }, Math.ceil(waktu), " dtk")), /* @__PURE__ */ React.createElement("div", { style: { height: 5, background: "rgba(255,255,255,0.1)", borderRadius: 99 } }, /* @__PURE__ */ React.createElement("div", { style: { height: "100%", width: `${ratio * 100}%`, background: ratio < 0.3 ? P.red : P.p2, transition: "width .1s linear" } }))), /* @__PURE__ */ React.createElement("div", { style: { marginBottom: 10 } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 700, color: P.p2, letterSpacing: 1, textTransform: "uppercase", marginBottom: 8 } }, "Susunanmu:"), /* @__PURE__ */ React.createElement(WordArea, { kata: disusun.map((x) => x.kata), onKlik: hapus, disabled: !!flash, accent: P.p2 })), /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 700, color: P.muted, letterSpacing: 1, textTransform: "uppercase", marginBottom: 8 } }, "Bank Kata:"), /* @__PURE__ */ React.createElement(WordArea, { kata: bank.map((x) => x.kata), onKlik: tambah, disabled: !!flash, accent: P.muted })), /* @__PURE__ */ React.createElement("div", { style: { minHeight: 30, marginTop: 12, textAlign: "center" } }, flash && /* @__PURE__ */ React.createElement("div", { className: "gf-pop", style: { fontWeight: 800, fontSize: 16, color: flash.ok ? P.green : P.red, fontFamily: "'Bricolage Grotesque',sans-serif" } }, flash.pesan)));
}
function SusunTatap({ lawan, onExit }) {
  const [ayat] = useState(() => siapkanAyat(5));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({ p1: 0, p2: 0 });
  const [state, setState] = useState({ p1: { disusun: [], bank: [] }, p2: { disusun: [], bank: [] } });
  const [waktu, setWaktu] = useState(25);
  const [winner, setWinner] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const a = ayat[idx];
  useEffect(() => {
    const bank = a.acak.map((k, i) => ({ kata: k, origIdx: i }));
    setState({ p1: { disusun: [], bank: [...bank] }, p2: { disusun: [], bank: [...bank] } });
  }, [idx]);
  const lanjut = useCallback((w) => {
    if (idx + 1 >= ayat.length) setSelesai(true);
    else {
      setIdx((i) => i + 1);
      setWinner(null);
      setWaktu(25);
    }
    ;
    if (w) setSkor((s) => __spreadProps(__spreadValues({}, s), { [w]: s[w] + 100 + Math.round(waktu / 25 * 80) }));
  }, [idx, ayat.length, waktu]);
  useEffect(() => {
    if (selesai || winner) return;
    timerRef.current = setInterval(() => {
      setWaktu((w) => {
        if (w <= 0.1) {
          clearInterval(timerRef.current);
          setWinner("seri");
          setTimeout(() => lanjut(null), 1500);
          return 0;
        }
        return +(w - 0.1).toFixed(1);
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
    setState((st) => __spreadProps(__spreadValues({}, st), { [pem]: { disusun: nd, bank: nb } }));
    if (nd.length === a.kata.length && nd.every((it, j) => it.kata === a.kata[j])) {
      clearInterval(timerRef.current);
      setWinner(pem);
      setTimeout(() => lanjut(pem), 1500);
    }
  };
  const hapus = (pem, i) => {
    if (winner) return;
    setState((st) => {
      const cur = st[pem];
      const item = cur.disusun[i];
      return __spreadProps(__spreadValues({}, st), { [pem]: { disusun: cur.disusun.filter((_, j) => j !== i), bank: [...cur.bank, item] } });
    });
  };
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.p1 >= skor.p2 ? P.p1 : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: "Kamu", val: skor.p1, color: P.p1, win: skor.p1 >= skor.p2 }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.p2, color: P.p2, win: skor.p2 >= skor.p1 })) });
  }
  const ratio = waktu / 25;
  const Panel = ({ pem, nama, color, flip }) => /* @__PURE__ */ React.createElement("div", { style: { flex: 1, padding: "12px 16px", display: "flex", flexDirection: "column", transform: flip ? "rotate(180deg)" : "none", background: winner === pem ? `${color}14` : "transparent" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 8 } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 6 } }, /* @__PURE__ */ React.createElement(Avatar, { nama, size: 24 }), /* @__PURE__ */ React.createElement("span", { style: { fontWeight: 800, fontSize: 13, color } }, nama)), /* @__PURE__ */ React.createElement("span", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 17, color } }, skor[pem])), /* @__PURE__ */ React.createElement("div", { style: { fontSize: 11, fontWeight: 700, color: P.muted, marginBottom: 5 } }, a.ref), /* @__PURE__ */ React.createElement("div", { style: { fontSize: 11, color: P.muted, marginBottom: 7 } }, "Susunanmu:", /* @__PURE__ */ React.createElement("br", null), /* @__PURE__ */ React.createElement(WordArea, { kata: state[pem].disusun.map((x) => x.kata), onKlik: (i) => hapus(pem, i), disabled: !!winner, accent: color })), /* @__PURE__ */ React.createElement("div", { style: { fontSize: 11, color: P.muted } }, "Bank Kata:", /* @__PURE__ */ React.createElement("br", null), /* @__PURE__ */ React.createElement(WordArea, { kata: state[pem].bank.map((x) => x.kata), onKlik: (i) => tambah(pem, i), disabled: !!winner, accent: P.muted })), winner === pem && /* @__PURE__ */ React.createElement("div", { className: "gf-pop", style: { textAlign: "center", marginTop: 6, fontWeight: 800, color, fontSize: 13 } }, "Tersusun! \u{1F389}"));
  return /* @__PURE__ */ React.createElement("div", { style: { display: "flex", flexDirection: "column", minHeight: 560 } }, /* @__PURE__ */ React.createElement(Panel, { pem: "p2", nama: lawan.nama, color: P.p2, flip: true }), /* @__PURE__ */ React.createElement("div", { style: { padding: "7px 14px", background: "rgba(0,0,0,0.3)", display: "flex", alignItems: "center", justifyContent: "center", gap: 12, position: "relative" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: __spreadProps(__spreadValues({}, gBtn), { padding: "4px 10px", position: "absolute", left: 8 }) }, "\u2190"), /* @__PURE__ */ React.createElement("span", { style: { fontSize: 11, fontWeight: 800, color: P.muted } }, "RONDE ", idx + 1, "/", ayat.length), /* @__PURE__ */ React.createElement("div", { style: { position: "relative", display: "grid", placeItems: "center" } }, /* @__PURE__ */ React.createElement(TimerRing, { ratio, danger: ratio < 0.3, size: 44 }), /* @__PURE__ */ React.createElement("div", { style: { position: "absolute", fontWeight: 800, fontSize: 13, color: ratio < 0.3 ? P.red : P.cream } }, Math.ceil(waktu)))), /* @__PURE__ */ React.createElement(Panel, { pem: "p1", nama: "Kamu", color: P.p1 }));
}
function SusunOnline({ lawan, sessionCode, onExit }) {
  const namaSaya = (window.__GAME_USER__ || { nama: "Kamu" }).nama;
  const [ayat] = useState(() => siapkanAyatSeed(5, sessionCode + ":susun"));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({ you: 0, op: 0 });
  const skorRef = useRef({ you: 0, op: 0 });
  const [disusun, setDisusun] = useState([]);
  const [bank, setBank] = useState([]);
  const [waktu, setWaktu] = useState(25);
  const [hasil, setHasil] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const timerRef = useRef();
  const resolvedRef = useRef(false);
  const waktuRef = useRef(25);
  const a = ayat[idx];
  useEffect(() => {
    skorRef.current = skor;
  }, [skor]);
  const lanjutKe = useCallback((nextIdx) => {
    if (nextIdx >= ayat.length) setSelesai(true);
    else {
      setIdx(nextIdx);
      setHasil(null);
      setWaktu(25);
    }
  }, [ayat.length]);
  const { sendMove, sendFinished } = useOnlineGame(sessionCode, (d) => {
    var _a, _b;
    if (d.user_id !== ((_a = window.__GAME_USER__) == null ? void 0 : _a.id)) {
      const p = d.payload || {};
      if (p.type === "ronde_selesai" && !resolvedRef.current) {
        resolvedRef.current = true;
        clearInterval(timerRef.current);
        const newOp = (_b = p.score) != null ? _b : skorRef.current.op + 100;
        setSkor((s) => {
          const ns = __spreadProps(__spreadValues({}, s), { op: newOp });
          skorRef.current = ns;
          return ns;
        });
        setHasil("op");
        setTimeout(() => lanjutKe(p.ronde + 1), 1600);
      }
    }
  }, (d) => {
    setSkor({ you: d.score_challenger, op: d.score_opponent });
    setSelesai(true);
  });
  useEffect(() => {
    setBank(a.acak.map((k, i) => ({ kata: k, origIdx: i })));
    setDisusun([]);
    resolvedRef.current = false;
    waktuRef.current = 25;
  }, [idx]);
  useEffect(() => {
    if (selesai || hasil) return;
    timerRef.current = setInterval(() => {
      setWaktu((w) => {
        const n = w <= 0.1 ? 0 : +(w - 0.1).toFixed(1);
        waktuRef.current = n;
        if (n === 0 && !resolvedRef.current) {
          resolvedRef.current = true;
          setHasil("seri");
          sendMove({ type: "ronde_selesai", ronde: idx, score: skorRef.current.you });
          setTimeout(() => lanjutKe(idx + 1), 1600);
        }
        return n;
      });
    }, 100);
    return () => clearInterval(timerRef.current);
  }, [idx, selesai, hasil, lanjutKe, sendMove]);
  const tambah = (i) => {
    if (hasil) return;
    const item = bank[i];
    const nd = [...disusun, item];
    setDisusun(nd);
    setBank((b) => b.filter((_, j) => j !== i));
    if (nd.length === a.kata.length && nd.every((it, j) => it.kata === a.kata[j])) {
      if (!resolvedRef.current) {
        resolvedRef.current = true;
        clearInterval(timerRef.current);
        const p = 100 + Math.round(waktuRef.current / 25 * 150);
        const newYou = skorRef.current.you + p;
        setSkor((s) => {
          const ns = __spreadProps(__spreadValues({}, s), { you: newYou });
          skorRef.current = ns;
          return ns;
        });
        setHasil("you");
        sendMove({ type: "ronde_selesai", ronde: idx, score: newYou });
        setTimeout(() => lanjutKe(idx + 1), 1600);
      }
    }
  };
  const hapus = (i) => {
    if (hasil) return;
    const item = disusun[i];
    setDisusun((d) => d.filter((_, j) => j !== i));
    setBank((b) => [...b, item]);
  };
  useEffect(() => {
    if (selesai) sendFinished(skorRef.current.you);
  }, [selesai]);
  if (selesai) {
    const w = skor.you === skor.op ? "Seri!" : skor.you > skor.op ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.you >= skor.op ? P.p2 : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: namaSaya, val: skor.you, color: P.gold, win: skor.you >= skor.op }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.op, color: P.p2, win: skor.op >= skor.you })) });
  }
  const ratio = waktu / 25;
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 26px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 12 } }, /* @__PURE__ */ React.createElement(Avatar, { nama: namaSaya, size: 32, ring: P.gold }), /* @__PURE__ */ React.createElement("div", { style: { position: "relative", display: "grid", placeItems: "center" } }, /* @__PURE__ */ React.createElement(TimerRing, { ratio, danger: ratio < 0.3, size: 42 }), /* @__PURE__ */ React.createElement("div", { style: { position: "absolute", fontWeight: 800, fontSize: 13, color: ratio < 0.3 ? P.red : P.cream } }, Math.ceil(waktu))), /* @__PURE__ */ React.createElement(Avatar, { nama: lawan.nama, size: 32, ring: P.p2 }))), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", fontSize: 13, fontWeight: 800, margin: "10px 0" } }, /* @__PURE__ */ React.createElement("span", { style: { color: P.gold } }, namaSaya, ": ", skor.you), /* @__PURE__ */ React.createElement("span", { style: { color: P.p2 } }, lawan.nama, ": ", skor.op)), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 4, padding: "8px 12px", borderRadius: 10, background: "rgba(255,255,255,0.04)", fontSize: 13, fontWeight: 700, color: P.muted } }, hasil ? /* @__PURE__ */ React.createElement("span", { style: { color: hasil === "you" ? P.green : hasil === "op" ? P.red : P.muted } }, hasil === "you" ? "Kamu berhasil duluan! \u{1F389}" : hasil === "op" ? `${lawan.nama} lebih cepat\u2026` : "Ronde seri") : /* @__PURE__ */ React.createElement("span", null, lawan.nama, " sedang menyusun\u2026")), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 14, fontSize: 12, fontWeight: 700, color: P.p2, letterSpacing: 1 } }, a.ref), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 8, marginBottom: 8 } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, color: P.muted, marginBottom: 6 } }, "Susunanmu:"), /* @__PURE__ */ React.createElement(WordArea, { kata: disusun.map((x) => x.kata), onKlik: hapus, disabled: !!hasil, accent: P.p2 })), /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, color: P.muted, marginBottom: 6 } }, "Bank Kata:"), /* @__PURE__ */ React.createElement(WordArea, { kata: bank.map((x) => x.kata), onKlik: tambah, disabled: !!hasil, accent: P.muted })));
}
const TEBAK_POIN = [400, 280, 180, 100];
function TebakSolo({ onExit }) {
  const [tokoh] = useState(() => siapkanTokoh(8));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState(0);
  const [benarTotal, setBenarTotal] = useState(0);
  const [clueIdx, setClueIdx] = useState(0);
  const [pilih, setPilih] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const t = tokoh[idx];
  const lanjut = useCallback(() => {
    if (idx + 1 >= tokoh.length) setSelesai(true);
    else {
      setIdx((i) => i + 1);
      setClueIdx(0);
      setPilih(null);
    }
  }, [idx, tokoh.length]);
  const jawab = (i) => {
    if (pilih !== null) return;
    setPilih(i);
    if (t.opsi[i] === t.jawaban) {
      const p = TEBAK_POIN[Math.min(clueIdx, TEBAK_POIN.length - 1)];
      setSkor((s) => s + p);
      setBenarTotal((b) => b + 1);
    }
    setTimeout(lanjut, 1600);
  };
  if (selesai) return /* @__PURE__ */ React.createElement(Hasil, { judul: "Selesai! \u{1F50D}", skor, accent: P.purple, onExit, baris: [{ label: "Tokoh tertebak", val: `${benarTotal}/${tokoh.length}` }, { label: "Akurasi", val: `${Math.round(benarTotal / tokoh.length * 100)}%` }, { label: "Total poin", val: skor.toLocaleString() }] });
  const maxPoin = TEBAK_POIN[Math.min(clueIdx, TEBAK_POIN.length - 1)];
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 28px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement(Pill, { color: P.purple, label: `${skor} pts` })), /* @__PURE__ */ React.createElement("div", { key: idx, className: "gf-pop", style: { marginTop: 20, padding: "20px", borderRadius: 20, background: "rgba(167,139,250,0.08)", border: "1px solid rgba(167,139,250,0.2)" } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 700, color: P.purple, letterSpacing: 1, textTransform: "uppercase" } }, "Siapa Aku? \xB7 Tokoh ", idx + 1, "/", tokoh.length), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 12, display: "grid", gap: 8 } }, t.clues.slice(0, clueIdx + 1).map((c, i) => /* @__PURE__ */ React.createElement("div", { key: i, style: { display: "flex", gap: 9, alignItems: "flex-start" } }, /* @__PURE__ */ React.createElement("span", { style: { color: P.purple, fontWeight: 800, flexShrink: 0 } }, "#", i + 1), /* @__PURE__ */ React.createElement("span", { style: { fontWeight: 600, fontSize: 15, color: P.cream, lineHeight: 1.4 } }, c)))), clueIdx < t.clues.length - 1 && pilih === null && /* @__PURE__ */ React.createElement("button", { onClick: () => setClueIdx((i) => i + 1), className: "gf-btn", style: { marginTop: 14, width: "100%", padding: "10px", borderRadius: 12, border: `1px solid ${P.purple}55`, background: `${P.purple}15`, color: P.purple, fontWeight: 700, fontSize: 13 } }, "Buka Clue Berikutnya (-", TEBAK_POIN[Math.min(clueIdx, TEBAK_POIN.length - 2)] - TEBAK_POIN[Math.min(clueIdx + 1, TEBAK_POIN.length - 1)], " poin)")), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 16 } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 700, color: P.muted, marginBottom: 8 } }, "Siapa tokoh ini? (Nilai maks: ", /* @__PURE__ */ React.createElement("span", { style: { color: P.purple } }, maxPoin), ")"), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 9 } }, t.opsi.map((op, i) => {
    let st = "idle";
    if (pilih !== null) {
      st = op === t.jawaban ? "benar" : i === pilih ? "salah" : "redup";
    }
    return /* @__PURE__ */ React.createElement(OptBtn, { key: i, text: op, idx: i, state: st, onClick: () => jawab(i), disabled: pilih !== null, delay: i * 0.05 });
  }))));
}
function TebakTatap({ lawan, onExit }) {
  const [tokoh] = useState(() => siapkanTokoh(6));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({ p1: 0, p2: 0 });
  const [clueIdx, setClueIdx] = useState(0);
  const [buzzed, setBuzzed] = useState(null);
  const [lock, setLock] = useState({ p1: false, p2: false });
  const [pilih, setPilih] = useState({ p1: null, p2: null });
  const [winner, setWinner] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const lockTimers = useRef({});
  const t = tokoh[idx];
  const lanjut = useCallback((w) => {
    if (idx + 1 >= tokoh.length) setSelesai(true);
    else {
      setIdx((i) => i + 1);
      setClueIdx(0);
      setBuzzed(null);
      setLock({ p1: false, p2: false });
      setPilih({ p1: null, p2: null });
      setWinner(null);
    }
    if (w) setSkor((s) => __spreadProps(__spreadValues({}, s), { [w]: s[w] + TEBAK_POIN[Math.min(clueIdx, 3)] }));
  }, [idx, tokoh.length, clueIdx]);
  const buzz = (pem) => {
    if (buzzed || lock[pem]) return;
    setBuzzed(pem);
  };
  const jawab = (pem, i) => {
    if (buzzed !== pem || pilih[pem] !== null) return;
    setPilih((p) => __spreadProps(__spreadValues({}, p), { [pem]: i }));
    if (t.opsi[i] === t.jawaban) {
      setWinner(pem);
      setTimeout(() => lanjut(pem), 1500);
    } else {
      setBuzzed(null);
      setLock((l) => __spreadProps(__spreadValues({}, l), { [pem]: true }));
      lockTimers.current[pem] = setTimeout(() => {
        setLock((l) => __spreadProps(__spreadValues({}, l), { [pem]: false }));
      }, 5e3);
    }
  };
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.p1 >= skor.p2 ? P.p1 : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: "Kamu", val: skor.p1, color: P.p1, win: skor.p1 >= skor.p2 }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.p2, color: P.p2, win: skor.p2 >= skor.p1 })) });
  }
  const Panel = ({ pem, nama, color, flip }) => {
    const isBuzzed = buzzed === pem;
    const isLocked = lock[pem];
    return /* @__PURE__ */ React.createElement("div", { style: { flex: 1, padding: "12px 16px", display: "flex", flexDirection: "column", transform: flip ? "rotate(180deg)" : "none", background: winner === pem ? `${color}14` : isBuzzed ? `${color}0a` : "transparent" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 8 } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 7 } }, /* @__PURE__ */ React.createElement(Avatar, { nama, size: 24 }), /* @__PURE__ */ React.createElement("span", { style: { fontWeight: 800, fontSize: 13, color } }, nama)), /* @__PURE__ */ React.createElement("span", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 17, color } }, skor[pem])), !isBuzzed && !winner && /* @__PURE__ */ React.createElement("button", { onClick: () => buzz(pem), disabled: !!buzzed || isLocked, className: "gf-btn", style: { padding: "14px", borderRadius: 14, border: `2px solid ${isLocked ? "rgba(255,255,255,0.1)" : color}`, background: isLocked ? "rgba(255,255,255,0.03)" : `${color}22`, color: isLocked ? P.muted : color, fontWeight: 800, fontSize: 17, cursor: isLocked ? "default" : "pointer", transition: "all .15s" } }, isLocked ? `Dikunci sementara\u2026` : `\u26A1 BUZZ!`), isBuzzed && !winner && /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 7 } }, t.opsi.map((op, i) => {
      const st = pilih[pem] === i ? op === t.jawaban ? "benar" : "salah" : "idle";
      return /* @__PURE__ */ React.createElement("button", { key: i, onClick: () => jawab(pem, i), className: "gf-btn", style: { padding: "11px", borderRadius: 12, border: `1.5px solid ${st === "benar" ? P.green : st === "salah" ? P.red : `${color}44`}`, background: st === "benar" ? `${P.green}22` : st === "salah" ? `${P.red}1f` : `${color}10`, color: P.cream, fontSize: 13.5, fontWeight: 700, cursor: "pointer" } }, op);
    })), winner === pem && /* @__PURE__ */ React.createElement("div", { className: "gf-pop", style: { textAlign: "center", marginTop: 6, fontWeight: 800, color, fontSize: 14 } }, "Benar! +", TEBAK_POIN[Math.min(clueIdx, 3)], " poin"), isLocked && /* @__PURE__ */ React.createElement("div", { className: "gf-shake", style: { textAlign: "center", marginTop: 6, color: P.red, fontSize: 12, fontWeight: 700 } }, "Salah! Dikunci 5 dtk \u2717"));
  };
  return /* @__PURE__ */ React.createElement("div", { style: { display: "flex", flexDirection: "column", minHeight: 560 } }, /* @__PURE__ */ React.createElement(Panel, { pem: "p2", nama: lawan.nama, color: P.p2, flip: true }), /* @__PURE__ */ React.createElement("div", { style: { background: "rgba(0,0,0,0.3)", padding: "8px 14px" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", justifyContent: "center", gap: 12, position: "relative" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: __spreadProps(__spreadValues({}, gBtn), { padding: "4px 10px", position: "absolute", left: 0 }) }, "\u2190"), /* @__PURE__ */ React.createElement("div", { style: { textAlign: "center" } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 11, fontWeight: 800, color: P.muted, letterSpacing: 1 } }, "TOKOH ", idx + 1, "/", tokoh.length))), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 8, padding: "10px 12px", borderRadius: 14, background: "rgba(167,139,250,0.08)", border: "1px solid rgba(167,139,250,0.2)" } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 11, fontWeight: 700, color: P.purple, marginBottom: 6 } }, "Clue terlihat semua pemain:"), t.clues.slice(0, clueIdx + 1).map((c, i) => /* @__PURE__ */ React.createElement("div", { key: i, style: { fontSize: 13, color: P.cream, fontWeight: 600, marginBottom: 4 } }, "#", i + 1, " ", c)), clueIdx < t.clues.length - 1 && !winner && /* @__PURE__ */ React.createElement("button", { onClick: () => setClueIdx((i) => i + 1), className: "gf-btn", style: { marginTop: 8, width: "100%", padding: "8px", borderRadius: 10, border: `1px solid ${P.purple}44`, background: `${P.purple}10`, color: P.purple, fontWeight: 700, fontSize: 12 } }, "Buka Clue Berikutnya"))), /* @__PURE__ */ React.createElement(Panel, { pem: "p1", nama: "Kamu", color: P.p1 }));
}
function TebakOnline({ lawan, sessionCode, onExit }) {
  const namaSaya = (window.__GAME_USER__ || { nama: "Kamu" }).nama;
  const [tokoh] = useState(() => siapkanTokohSeed(6, sessionCode + ":tebak"));
  const [idx, setIdx] = useState(0);
  const [skor, setSkor] = useState({ you: 0, op: 0 });
  const skorRef = useRef({ you: 0, op: 0 });
  const [clueIdx, setClueIdx] = useState(0);
  const [pilih, setPilih] = useState(null);
  const [youLock, setYouLock] = useState(false);
  const [hasil, setHasil] = useState(null);
  const [selesai, setSelesai] = useState(false);
  const resolvedRef = useRef(false);
  const t = tokoh[idx];
  useEffect(() => {
    skorRef.current = skor;
  }, [skor]);
  const lanjut = useCallback(() => {
    if (idx + 1 >= tokoh.length) setSelesai(true);
    else {
      setIdx((i) => i + 1);
      setClueIdx(0);
      setPilih(null);
      setYouLock(false);
      setHasil(null);
    }
  }, [idx, tokoh.length]);
  const { sendMove, sendFinished } = useOnlineGame(sessionCode, (d) => {
    var _a, _b;
    if (d.user_id !== ((_a = window.__GAME_USER__) == null ? void 0 : _a.id)) {
      const p = d.payload || {};
      if (p.type === "answered_correct" && !resolvedRef.current) {
        resolvedRef.current = true;
        const newOp = (_b = p.score) != null ? _b : skorRef.current.op + TEBAK_POIN[Math.min(clueIdx, 3)];
        setSkor((s) => {
          const ns = __spreadProps(__spreadValues({}, s), { op: newOp });
          skorRef.current = ns;
          return ns;
        });
        setHasil("op");
        setTimeout(lanjut, 1600);
      }
    }
  }, (d) => {
    setSkor({ you: d.score_challenger, op: d.score_opponent });
    setSelesai(true);
  });
  useEffect(() => {
    resolvedRef.current = false;
  }, [idx]);
  const jawab = (i) => {
    if (resolvedRef.current || pilih !== null) return;
    setPilih(i);
    if (t.opsi[i] === t.jawaban) {
      resolvedRef.current = true;
      const p = TEBAK_POIN[Math.min(clueIdx, 3)];
      const newYou = skorRef.current.you + p;
      setSkor((s) => {
        const ns = __spreadProps(__spreadValues({}, s), { you: newYou });
        skorRef.current = ns;
        return ns;
      });
      setHasil("you");
      sendMove({ type: "answered_correct", ronde: idx, score: newYou });
      setTimeout(lanjut, 1600);
    } else {
      setYouLock(true);
      sendMove({ type: "answered_wrong", ronde: idx, score: skorRef.current.you });
    }
  };
  useEffect(() => {
    if (selesai) sendFinished(skorRef.current.you);
  }, [selesai]);
  if (selesai) {
    const w = skor.you === skor.op ? "Seri!" : skor.you > skor.op ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.you >= skor.op ? P.purple : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: namaSaya, val: skor.you, color: P.gold, win: skor.you >= skor.op }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.op, color: P.p2, win: skor.op >= skor.you })) });
  }
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 26px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center" } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 10 } }, /* @__PURE__ */ React.createElement("span", { style: { fontWeight: 800, fontSize: 15, color: P.gold } }, skor.you), /* @__PURE__ */ React.createElement("span", { style: { color: P.muted } }, "vs"), /* @__PURE__ */ React.createElement("span", { style: { fontWeight: 800, fontSize: 15, color: P.p2 } }, skor.op))), /* @__PURE__ */ React.createElement("div", { key: idx, style: { marginTop: 16, padding: "16px", borderRadius: 18, background: "rgba(167,139,250,0.08)", border: "1px solid rgba(167,139,250,0.2)" } }, /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 700, color: P.purple, letterSpacing: 1, marginBottom: 10 } }, "SIAPA AKU? \xB7 Tokoh ", idx + 1, "/", tokoh.length), t.clues.slice(0, clueIdx + 1).map((c, i) => /* @__PURE__ */ React.createElement("div", { key: i, style: { display: "flex", gap: 8, marginBottom: 7 } }, /* @__PURE__ */ React.createElement("span", { style: { color: P.purple, fontWeight: 800 } }, "#", i + 1), /* @__PURE__ */ React.createElement("span", { style: { fontSize: 15, fontWeight: 600, color: P.cream, lineHeight: 1.4 } }, c))), clueIdx < t.clues.length - 1 && !hasil && /* @__PURE__ */ React.createElement("button", { onClick: () => setClueIdx((i) => i + 1), className: "gf-btn", style: { marginTop: 10, width: "100%", padding: "9px", borderRadius: 12, border: `1px solid ${P.purple}44`, background: `${P.purple}12`, color: P.purple, fontWeight: 700, fontSize: 13 } }, "Buka Clue Berikutnya")), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 12, padding: "8px 12px", borderRadius: 10, background: "rgba(255,255,255,0.04)", fontSize: 13, fontWeight: 700 } }, hasil ? /* @__PURE__ */ React.createElement("span", { style: { color: hasil === "you" ? P.green : P.red } }, hasil === "you" ? "Kamu benar duluan! \u{1F389}" : hasil === "op" ? `${lawan.nama} lebih cepat\u2026` : "Ronde seri") : /* @__PURE__ */ React.createElement("span", { style: { color: P.muted } }, lawan.nama, " sedang menebak\u2026")), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 9, marginTop: 14 } }, t.opsi.map((op, i) => {
    let st = "idle";
    if (hasil || youLock) {
      st = op === t.jawaban ? "benar" : i === pilih ? "salah" : "redup";
    } else if (i === pilih) st = "salah";
    return /* @__PURE__ */ React.createElement(OptBtn, { key: i, text: op, idx: i, state: st, onClick: () => jawab(i), disabled: !!hasil || youLock || pilih !== null, delay: i * 0.04 });
  })), youLock && !hasil && /* @__PURE__ */ React.createElement("div", { className: "gf-shake", style: { textAlign: "center", marginTop: 8, color: P.red, fontSize: 13, fontWeight: 700 } }, "Jawaban salah \u2717"));
}
function KartuView({ kartu, terbuka, matched, onClick, disabled }) {
  const show = terbuka || matched;
  return /* @__PURE__ */ React.createElement("div", { onClick: disabled || matched ? void 0 : onClick, className: "gf-card-wrap", style: { cursor: disabled || matched ? "default" : "pointer", height: 80 } }, /* @__PURE__ */ React.createElement("div", { className: `gf-card-inner${show ? " flipped" : ""}`, style: { height: "100%" } }, /* @__PURE__ */ React.createElement("div", { className: "gf-card-face", style: { background: "rgba(255,255,255,0.06)", border: "1.5px solid rgba(255,255,255,0.12)" } }, /* @__PURE__ */ React.createElement("span", { style: { fontSize: 22 } }, "\u2726")), /* @__PURE__ */ React.createElement("div", { className: "gf-card-face gf-card-back", style: { background: matched ? "rgba(74,222,128,0.15)" : "rgba(37,99,235,0.15)", border: `1.5px solid ${matched ? P.green : "rgba(99,102,241,0.4)"}` } }, /* @__PURE__ */ React.createElement("span", { style: { fontSize: 11.5, fontWeight: 700, color: matched ? P.green : P.cream, lineHeight: 1.3, textAlign: "center", padding: "4px" } }, kartu.isi))));
}
function MemorySolo({ onExit }) {
  const [cards] = useState(() => siapkanKartu(8));
  const [terbuka, setTerbuka] = useState([]);
  const [matched, setMatched] = useState([]);
  const [langkah, setLangkah] = useState(0);
  const [waktu, setWaktu] = useState(0);
  const [selesai, setSelesai] = useState(false);
  const checkRef = useRef(false);
  const timerRef = useRef();
  useEffect(() => {
    timerRef.current = setInterval(() => setWaktu((w) => w + 1), 1e3);
    return () => clearInterval(timerRef.current);
  }, []);
  useEffect(() => {
    if (matched.length === cards.length && cards.length > 0) {
      clearInterval(timerRef.current);
      setSelesai(true);
    }
  }, [matched, cards.length]);
  const klik = (i) => {
    if (checkRef.current || terbuka.includes(i) || matched.includes(i) || terbuka.length >= 2) return;
    const next = [...terbuka, i];
    setTerbuka(next);
    setLangkah((l) => l + 1);
    if (next.length === 2) {
      checkRef.current = true;
      const [a, b] = next;
      if (cards[a].pair === cards[b].pair) {
        setMatched((m) => [...m, a, b]);
        setTerbuka([]);
        checkRef.current = false;
      } else {
        setTimeout(() => {
          setTerbuka([]);
          checkRef.current = false;
        }, 900);
      }
    }
  };
  const mnt = String(Math.floor(waktu / 60)).padStart(2, "0"), dtk = String(waktu % 60).padStart(2, "0");
  const skor = Math.max(0, 2e3 - langkah * 20 - waktu * 5);
  if (selesai) return /* @__PURE__ */ React.createElement(Hasil, { judul: "Semua Cocok! \u{1F0CF}", skor, accent: P.orange, onExit, baris: [{ label: "Waktu", val: `${mnt}:${dtk}` }, { label: "Langkah", val: langkah }, { label: "Pasangan", val: `${matched.length / 2}/8` }] });
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 28px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 14 } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12 } }, /* @__PURE__ */ React.createElement(Pill, { color: P.orange, label: `\u23F1 ${mnt}:${dtk}` }), /* @__PURE__ */ React.createElement(Pill, { color: P.green, label: `${matched.length / 2}/8 \u2713` }))), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gridTemplateColumns: "repeat(4,1fr)", gap: 8 } }, cards.map((c, i) => /* @__PURE__ */ React.createElement(KartuView, { key: c.id, kartu: c, terbuka: terbuka.includes(i), matched: matched.includes(i), onClick: () => klik(i), disabled: terbuka.length === 2 && !terbuka.includes(i) }))), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 12, textAlign: "center", color: P.muted, fontSize: 13, fontWeight: 600 } }, langkah, " langkah \xB7 Skor estimasi: ", Math.max(0, 2e3 - langkah * 20 - waktu * 5)));
}
function MemoryTatap({ lawan, onExit }) {
  const [cards] = useState(() => siapkanKartu(8));
  const [terbuka, setTerbuka] = useState([]);
  const [matched, setMatched] = useState([]);
  const [giliranP, setGiliranP] = useState("p1");
  const [skor, setSkor] = useState({ p1: 0, p2: 0 });
  const [langkah, setLangkah] = useState(0);
  const [selesai, setSelesai] = useState(false);
  const checkRef = useRef(false);
  useEffect(() => {
    if (matched.length === cards.length && cards.length > 0) setSelesai(true);
  }, [matched, cards.length]);
  const klik = (i) => {
    if (checkRef.current || terbuka.includes(i) || matched.includes(i) || terbuka.length >= 2) return;
    const next = [...terbuka, i];
    setTerbuka(next);
    setLangkah((l) => l + 1);
    if (next.length === 2) {
      checkRef.current = true;
      const [a, b] = next;
      if (cards[a].pair === cards[b].pair) {
        setSkor((s) => __spreadProps(__spreadValues({}, s), { [giliranP]: s[giliranP] + 1 }));
        setMatched((m) => [...m, a, b]);
        setTerbuka([]);
        checkRef.current = false;
      } else {
        setTimeout(() => {
          setTerbuka([]);
          setGiliranP((g) => g === "p1" ? "p2" : "p1");
          checkRef.current = false;
        }, 900);
      }
    }
  };
  if (selesai) {
    const w = skor.p1 === skor.p2 ? "Seri!" : skor.p1 > skor.p2 ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.p1 >= skor.p2 ? P.p1 : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: "Kamu", val: skor.p1, color: P.p1, win: skor.p1 >= skor.p2 }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.p2, color: P.p2, win: skor.p2 >= skor.p1 })) });
  }
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 28px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 10 } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 800, color: P.muted, letterSpacing: 1 } }, "MEMORY MATCH")), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", marginBottom: 12, padding: "10px 14px", borderRadius: 14, background: "rgba(255,255,255,0.04)", border: "1px solid rgba(255,255,255,0.08)" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 8 } }, /* @__PURE__ */ React.createElement(Avatar, { nama: "Kamu", size: 28, ring: giliranP === "p1" ? P.orange : void 0 }), /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 800, fontSize: 13, color: giliranP === "p1" ? P.orange : P.cream } }, "Kamu ", giliranP === "p1" ? "\u2190 giliran" : ""), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 18, color: P.orange } }, skor.p1, " pasang"))), /* @__PURE__ */ React.createElement("div", { style: { textAlign: "right", display: "flex", alignItems: "center", gap: 8 } }, /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 800, fontSize: 13, color: giliranP === "p2" ? P.p2 : P.cream } }, giliranP === "p2" ? "giliran \u2192" : "", " ", lawan.nama), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 18, color: P.p2 } }, skor.p2, " pasang")), /* @__PURE__ */ React.createElement(Avatar, { nama: lawan.nama, size: 28, ring: giliranP === "p2" ? P.p2 : void 0 }))), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gridTemplateColumns: "repeat(4,1fr)", gap: 8 } }, cards.map((c, i) => /* @__PURE__ */ React.createElement(KartuView, { key: c.id, kartu: c, terbuka: terbuka.includes(i), matched: matched.includes(i), onClick: () => klik(i), disabled: terbuka.length === 2 && !terbuka.includes(i) }))), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 10, textAlign: "center", fontSize: 13, fontWeight: 700, color: P.muted } }, langkah, " langkah \xB7 ", matched.length / 2, "/8 pasang ditemukan"));
}
function MemoryOnline({ lawan, sessionCode, onExit }) {
  const namaSaya = (window.__GAME_USER__ || { nama: "Kamu" }).nama;
  const [cards] = useState(() => siapkanKartuSeed(8, sessionCode + ":memory"));
  const [terbuka, setTerbuka] = useState([]);
  const [matched, setMatched] = useState([]);
  const [giliranKamu, setGiliranKamu] = useState(true);
  const [skor, setSkor] = useState({ you: 0, op: 0 });
  const [selesai, setSelesai] = useState(false);
  const checkRef = useRef(false);
  const matchedRef = useRef([]);
  const skorRef = useRef({ you: 0, op: 0 });
  useEffect(() => {
    matchedRef.current = matched;
  }, [matched]);
  useEffect(() => {
    skorRef.current = skor;
  }, [skor]);
  const { sendMove, sendFinished } = useOnlineGame(sessionCode, (d) => {
    var _a;
    if (d.user_id !== ((_a = window.__GAME_USER__) == null ? void 0 : _a.id)) {
      const p = d.payload || {};
      if (p.type === "flip") {
        const [a, b] = [p.card_a, p.card_b];
        if (a === void 0 || b === void 0) return;
        checkRef.current = true;
        setTerbuka([a, b]);
        setTimeout(() => {
          var _a2, _b;
          if (((_a2 = cards[a]) == null ? void 0 : _a2.pair) === ((_b = cards[b]) == null ? void 0 : _b.pair)) {
            setSkor((s) => {
              const ns = __spreadProps(__spreadValues({}, s), { op: s.op + 1 });
              skorRef.current = ns;
              return ns;
            });
            setMatched((m) => {
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
              setGiliranKamu(true);
              checkRef.current = false;
            }, 700);
          }
        }, 900);
      }
    }
  }, (d) => {
    setSkor({ you: d.score_challenger, op: d.score_opponent });
    setSelesai(true);
  });
  const klik = (i) => {
    if (!giliranKamu || checkRef.current || terbuka.includes(i) || matched.includes(i) || terbuka.length >= 2) return;
    const next = [...terbuka, i];
    setTerbuka(next);
    if (next.length === 2) {
      checkRef.current = true;
      const [a, b] = next;
      sendMove({ type: "flip", card_a: a, card_b: b, score: skorRef.current.you });
      if (cards[a].pair === cards[b].pair) {
        const newYou = skorRef.current.you + 1;
        setSkor((s) => {
          const ns = __spreadProps(__spreadValues({}, s), { you: newYou });
          skorRef.current = ns;
          return ns;
        });
        setMatched((m) => {
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
          setGiliranKamu(false);
          checkRef.current = false;
        }, 900);
      }
    }
  };
  useEffect(() => {
    if (selesai) sendFinished(skorRef.current.you);
  }, [selesai]);
  if (selesai) {
    const w = skor.you === skor.op ? "Seri!" : skor.you > skor.op ? "Kamu Menang! \u{1F3C6}" : `${lawan.nama} Menang! \u{1F3C6}`;
    return /* @__PURE__ */ React.createElement(Hasil, { judul: w, skor: null, accent: skor.you >= skor.op ? P.orange : P.p2, onExit, custom: /* @__PURE__ */ React.createElement("div", { style: { display: "flex", gap: 12, justifyContent: "center", marginTop: 8 } }, /* @__PURE__ */ React.createElement(ScorePill, { name: namaSaya, val: skor.you, color: P.gold, win: skor.you >= skor.op }), /* @__PURE__ */ React.createElement(ScorePill, { name: lawan.nama, val: skor.op, color: P.p2, win: skor.op >= skor.you })) });
  }
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "18px 20px 28px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 10 } }, /* @__PURE__ */ React.createElement("button", { onClick: onExit, className: "gf-btn", style: gBtn }, "\u2190 Keluar"), /* @__PURE__ */ React.createElement("div", { style: { fontSize: 12, fontWeight: 800, color: giliranKamu ? P.orange : P.p2 } }, giliranKamu ? "Giliranmu \u2014 buka 2 kartu" : "Giliran lawan\u2026")), /* @__PURE__ */ React.createElement("div", { style: { display: "flex", justifyContent: "space-between", marginBottom: 12, padding: "10px 14px", borderRadius: 14, background: "rgba(255,255,255,0.04)" } }, /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 700, fontSize: 13, color: P.orange } }, namaSaya), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 22, color: P.orange } }, skor.you)), /* @__PURE__ */ React.createElement("div", { style: { textAlign: "right" } }, /* @__PURE__ */ React.createElement("div", { style: { fontWeight: 700, fontSize: 13, color: P.p2 } }, lawan.nama), /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 22, color: P.p2 } }, skor.op))), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gridTemplateColumns: "repeat(4,1fr)", gap: 8 } }, cards.map((c, i) => /* @__PURE__ */ React.createElement(KartuView, { key: c.id, kartu: c, terbuka: terbuka.includes(i), matched: matched.includes(i), onClick: () => klik(i), disabled: !giliranKamu || terbuka.length === 2 && !terbuka.includes(i) }))), /* @__PURE__ */ React.createElement("div", { style: { marginTop: 10, textAlign: "center", fontSize: 13, fontWeight: 600, color: P.muted } }, matched.length / 2, "/8 pasang ditemukan"));
}
function GameCard({ g, onClick }) {
  const [h, setH] = useState(false);
  return /* @__PURE__ */ React.createElement("button", { onClick, onMouseEnter: () => setH(true), onMouseLeave: () => setH(false), className: "gf-btn gf-rise", style: { display: "flex", alignItems: "center", gap: 14, padding: 18, borderRadius: 20, border: `1px solid ${h ? g.warna : "rgba(255,255,255,0.1)"}`, background: h ? `${g.warna}08` : "rgba(255,255,255,0.03)", cursor: "pointer", color: P.cream, textAlign: "left", width: "100%" } }, /* @__PURE__ */ React.createElement("div", { style: { width: 52, height: 52, borderRadius: 16, background: `${g.warna}22`, display: "grid", placeItems: "center", fontSize: 26, flexShrink: 0 } }, g.ikon), /* @__PURE__ */ React.createElement("div", { style: { flex: 1 } }, /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 18, color: P.cream } }, g.judul), /* @__PURE__ */ React.createElement("div", { style: { color: P.muted, fontSize: 13, fontWeight: 600, marginTop: 2 } }, g.desc)), /* @__PURE__ */ React.createElement("span", { style: { color: g.warna, fontSize: 20 } }, "\u203A"));
}
function Hub({ onGame, onLeaderboard }) {
  return /* @__PURE__ */ React.createElement("div", { style: { padding: "26px 20px 32px", maxWidth: 460, margin: "0 auto" } }, /* @__PURE__ */ React.createElement("div", { style: { display: "flex", alignItems: "center", gap: 12, marginBottom: 28 } }, /* @__PURE__ */ React.createElement("div", { style: { width: 46, height: 46, borderRadius: 16, background: `linear-gradient(135deg,${P.gold},#E8902F)`, display: "grid", placeItems: "center", boxShadow: `0 8px 24px rgba(245,196,81,0.35)`, flexShrink: 0 } }, /* @__PURE__ */ React.createElement("span", { style: { fontSize: 22 } }, "\u{1F3AE}")), /* @__PURE__ */ React.createElement("div", null, /* @__PURE__ */ React.createElement("div", { style: { fontFamily: "'Bricolage Grotesque',sans-serif", fontWeight: 800, fontSize: 22, color: P.cream, letterSpacing: -0.4 } }, "Game Rohani"), /* @__PURE__ */ React.createElement("div", { style: { fontSize: 13, color: P.muted, fontWeight: 600 } }, "4 mini-game \xB7 Asah firman"))), /* @__PURE__ */ React.createElement("div", { style: { display: "grid", gap: 12 } }, GAME_DEFS.map((g, i) => /* @__PURE__ */ React.createElement(GameCard, { key: g.id, g, onClick: () => onGame(g) }))), /* @__PURE__ */ React.createElement("button", { onClick: onLeaderboard, className: "gf-btn", style: { width: "100%", marginTop: 18, padding: "14px", borderRadius: 18, border: `1px solid ${P.gold}44`, background: `${P.gold}0d`, color: P.gold, fontWeight: 800, fontSize: 15, cursor: "pointer", display: "flex", alignItems: "center", justifyContent: "center", gap: 10 } }, /* @__PURE__ */ React.createElement("span", null, "\u{1F3C6}"), " Papan Peringkat Mingguan"));
}
function GameFeature() {
  const [screen, setScreen] = useState("hub");
  const [game, setGame] = useState(null);
  const [mode, setMode] = useState(null);
  const [lawan, setLawan] = useState(null);
  const [sessionCode, setSessionCode] = useState(null);
  const [notif, setNotif] = useState(null);
  const pulang = () => {
    setScreen("hub");
    setGame(null);
    setMode(null);
    setLawan(null);
    setSessionCode(null);
  };
  const mulaiGame = (g) => {
    setGame(g);
    setScreen("cara");
  };
  const pilihCara = (m) => {
    setMode(m);
    if (m === "solo") setScreen("main");
    else setScreen("lawan");
  };
  const pilihLawan = (l) => {
    setLawan(l);
    if (mode === "online") setScreen("lobi");
    else setScreen("main");
  };
  useEffect(() => {
    var _a;
    const userId = (_a = window.__GAME_USER__) == null ? void 0 : _a.id;
    if (!userId) return;
    const pusher = getPusher();
    if (!pusher) return;
    const ch = pusher.subscribe("private-game-user." + userId);
    ch.bind("challenged", (d) => {
      setNotif(d);
    });
    return () => {
      pusher.unsubscribe("private-game-user." + userId);
    };
  }, []);
  const terimaNotif = async () => {
    if (!notif) return;
    try {
      const res = await apiPost("/game/respond", { session_code: notif.session_code, accept: true });
      setGame({ id: res.game_type || notif.game_type });
      setLawan({ id: notif.challenger_id, nama: notif.challenger_name });
      setSessionCode(notif.session_code);
      setMode("online");
      setScreen("main");
      setNotif(null);
    } catch (e) {
      setNotif(null);
    }
  };
  const tolakNotif = async () => {
    if (!notif) return;
    try {
      await apiPost("/game/respond", { session_code: notif.session_code, accept: false });
    } catch (e) {
    }
    setNotif(null);
  };
  const GameMain = () => {
    if (!game && !sessionCode) return null;
    if (mode === "solo") {
      if (game.id === "kuis") return /* @__PURE__ */ React.createElement(KuisSolo, { onExit: pulang });
      if (game.id === "susun") return /* @__PURE__ */ React.createElement(SusunSolo, { onExit: pulang });
      if (game.id === "tebak") return /* @__PURE__ */ React.createElement(TebakSolo, { onExit: pulang });
      if (game.id === "memory") return /* @__PURE__ */ React.createElement(MemorySolo, { onExit: pulang });
    }
    if (mode === "tatap") {
      if (game.id === "kuis") return /* @__PURE__ */ React.createElement(KuisTatap, { lawan, onExit: pulang });
      if (game.id === "susun") return /* @__PURE__ */ React.createElement(SusunTatap, { lawan, onExit: pulang });
      if (game.id === "tebak") return /* @__PURE__ */ React.createElement(TebakTatap, { lawan, onExit: pulang });
      if (game.id === "memory") return /* @__PURE__ */ React.createElement(MemoryTatap, { lawan, onExit: pulang });
    }
    if (mode === "online") {
      const gType = (game == null ? void 0 : game.id) || (notif == null ? void 0 : notif.game_type) || "kuis";
      if (gType === "kuis") return /* @__PURE__ */ React.createElement(KuisOnline, { lawan, sessionCode, onExit: pulang });
      if (gType === "susun") return /* @__PURE__ */ React.createElement(SusunOnline, { lawan, sessionCode, onExit: pulang });
      if (gType === "tebak") return /* @__PURE__ */ React.createElement(TebakOnline, { lawan, sessionCode, onExit: pulang });
      if (gType === "memory") return /* @__PURE__ */ React.createElement(MemoryOnline, { lawan, sessionCode, onExit: pulang });
    }
    return null;
  };
  return /* @__PURE__ */ React.createElement("div", { style: { minHeight: 560, width: "100%", background: `radial-gradient(120% 90% at 50% -10%,${P.nightSoft} 0%,${P.night} 55%,#120D2E 100%)`, color: P.cream, fontFamily: "'Plus Jakarta Sans',sans-serif", borderRadius: 24, overflow: "hidden", position: "relative" } }, /* @__PURE__ */ React.createElement(GStyles, null), screen === "hub" && /* @__PURE__ */ React.createElement(Hub, { onGame: mulaiGame, onLeaderboard: () => setScreen("leaderboard") }), screen === "leaderboard" && /* @__PURE__ */ React.createElement(PapanPeringkat, { onBack: pulang }), screen === "cara" && game && /* @__PURE__ */ React.createElement(PilihCara, { game, onBack: pulang, onPick: pilihCara }), screen === "lawan" && game && /* @__PURE__ */ React.createElement(PilihLawan, { game, mode, onBack: () => setScreen("cara"), onPick: pilihLawan }), screen === "lobi" && game && lawan && /* @__PURE__ */ React.createElement(LobiOnline, { lawan, game, onBack: () => setScreen("lawan"), onMulai: (code) => {
    setSessionCode(code);
    setScreen("main");
  }, onDeclined: () => setScreen("lawan") }), screen === "main" && /* @__PURE__ */ React.createElement(GameMain, null), notif && screen !== "main" && /* @__PURE__ */ React.createElement(NotifTantangan, { notif, onTerima: terimaNotif, onTolak: tolakNotif }));
}
const rootEl = document.getElementById("game-root");
if (rootEl) ReactDOM.createRoot(rootEl).render(/* @__PURE__ */ React.createElement(GameFeature, null));
