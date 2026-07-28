"""
Pixel-art GIF: figur Yesus dengan pose menenangkan/menghibur (tangan di
dada, senyum lembut) — dipakai untuk hasil KALAH, supaya tetap positif
("tidak apa-apa, coba lagi") alih-alih terkesan sedih/mengejek.
Struktur sama seperti versi bersorak: grid manual -> outline otomatis -> upscale.
"""

from PIL import Image

W, H = 32, 35
SCALE = 12
DY = 4

PALETTE = {
    'K': (255, 228, 140),   # halo
    'S': (231, 186, 141),   # kulit
    's': (201, 153, 110),   # bayangan kulit
    'c': (213, 141, 121),   # rona pipi
    'E': (55, 38, 28),      # mata
    'h': (99, 62, 38),      # rambut
    'b': (124, 82, 49),     # janggut
    'O': (86, 46, 40),      # mulut
    'W': (246, 243, 233),   # jubah putih
    'w': (209, 203, 188),   # lipatan jubah
    'V': (74, 106, 168),    # mantel biru
    'R': (176, 60, 56),     # selempang merah
}

OUTLINE = (34, 24, 44)
BG = (24, 20, 46)
FLOOR = (40, 33, 68)
SHADOW = (17, 14, 32)
GLOW = (60, 54, 84)
GLOW2 = (46, 41, 70)

BASE = [
    ".............KKKKKK.............",  # 0  halo (busur atas)
    "...........KK......KK...........",  # 1  halo (sisi)
    ".............KKKKKK.............",  # 2  halo (busur bawah)
    "................................",  # 3
    "..........hhhhhhhhhhhh..........",  # 4  rambut
    "..........hhSSSSSSSShh..........",  # 5
    "..........hSSSSSSSSSSh..........",  # 6
    "..........hSSESSSSESSh..........",  # 7  mata (tenang)
    "..........hScSSssSScSh..........",  # 8  pipi + hidung
    "..........hSbbbbbbbbSh..........",  # 9  kumis
    "..........hbbbOOOObbbh..........",  # 10 senyum lembut
    "..........bbbbbbbbbbbb..........",  # 11
    "..........hbbbbbbbbbbh..........",  # 12
    "...........bbbbbbbbbb...........",  # 13
    ".........VVhbbbbbbbbhVV.........",  # 14 bahu + mantel
    ".........VVWhbbbbbbhWVV.........",  # 15
    "..........VWWbbbbbbWWV..........",  # 16
    "..........VWWWbbbbWWWV..........",  # 17 ujung janggut
    "..........VWWWWWWWWWWV..........",  # 18
    "..........VWWWWWWWWWWV..........",  # 19
    "..........WWWWWWWWWWWW..........",  # 20
    "..........RRRRRRRRRRRR..........",  # 21 selempang
    ".........WWWWWWWWWWWWWW.........",  # 22
    ".........WWWWwwWWwwWWWW.........",  # 23
    "........WWWWWWWWWWWWWWWW........",  # 24
    "........WWWWWwwWWwwWWWWW........",  # 25
    ".......WWWWWWWWWWWWWWWWWW.......",  # 26 kelim jubah
    "...........SSSS..SSSS...........",  # 27 kaki telanjang
]


def mirror(pixels):
    return [(31 - x, y, ch) for (x, y, ch) in pixels]


# Kedua tangan menyilang lembut di dada — pose "tenang, tidak apa-apa"
_left_chest = [
    (8, 14, 'V'), (8, 13, 'W'), (9, 13, 'W'), (9, 12, 'W'),
    (10, 12, 'W'), (11, 12, 'w'), (12, 12, 'w'),
    (12, 11, 'S'), (13, 11, 'S'), (13, 10, 'S'), (14, 10, 'S'),
]
ARMS_CHEST = _left_chest + mirror(_left_chest)

SPARKLES = []  # tidak ada kilau — pose tenang, bukan perayaan

MOTES = [
    (3, 4, (198, 190, 224)), (9, 9, (170, 164, 200)), (14, 6, (198, 190, 224)),
    (20, 12, (170, 164, 200)), (25, 8, (198, 190, 224)), (30, 13, (170, 164, 200)),
    (6, 16, (170, 164, 200)), (23, 18, (198, 190, 224)), (17, 22, (170, 164, 200)),
    (1, 24, (198, 190, 224)), (11, 27, (170, 164, 200)), (28, 25, (198, 190, 224)),
]


def build_sprite(bounce, arms):
    layer = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    px = layer.load()
    for y, row in enumerate(BASE):
        for x, ch in enumerate(row):
            if ch != '.':
                yy = y + DY + bounce
                if 0 <= yy < H:
                    px[x, yy] = PALETTE[ch] + (255,)
    for (x, y, ch) in arms:
        yy = y + DY + bounce
        if 0 <= yy < H and 0 <= x < W:
            px[x, yy] = PALETTE[ch] + (255,)
    return layer


def add_outline(layer):
    px = layer.load()
    out = layer.copy()
    opx = out.load()
    for y in range(H):
        for x in range(W):
            if px[x, y][3] != 0:
                continue
            for dx, dy in ((1, 0), (-1, 0), (0, 1), (0, -1)):
                nx, ny = x + dx, y + dy
                if 0 <= nx < W and 0 <= ny < H and px[nx, ny][3] != 0:
                    opx[x, y] = OUTLINE + (255,)
                    break
    return out


def make_frame(bounce, arms, sparkle, tick):
    bg = Image.new("RGBA", (W, H), BG + (255,))
    bpx = bg.load()

    cx, cy = 15.5, 9 + DY + bounce
    for y in range(H):
        for x in range(W):
            d = ((x - cx) ** 2 + (y - cy) ** 2) ** 0.5
            if d <= 7.5:
                bpx[x, y] = GLOW + (255,)
            elif d <= 10.5:
                bpx[x, y] = GLOW2 + (255,)

    for y in range(32, H):
        for x in range(W):
            bpx[x, y] = FLOOR + (255,)

    for (x, y0, col) in MOTES:
        y = (y0 + tick) % 32  # melayang lambat — suasana tenang, bukan ramai
        bpx[x % W, y] = col + (255,)

    shrink = abs(bounce)
    x0, x1 = 9 + shrink, 22 - shrink
    for x in range(x0, x1 + 1):
        bpx[x, 32] = SHADOW + (255,)

    sprite = add_outline(build_sprite(bounce, arms))
    bg.alpha_composite(sprite)

    return bg.convert("RGB").resize((W * SCALE, H * SCALE), Image.NEAREST)


# Napas pelan naik-turun, tangan tetap di dada sepanjang animasi — tenang,
# tidak ada lompatan/kilau seperti versi bersorak.
SEQ = [
    (0,  ARMS_CHEST, False),
    (0,  ARMS_CHEST, False),
    (-1, ARMS_CHEST, False),
    (-1, ARMS_CHEST, False),
    (0,  ARMS_CHEST, False),
    (0,  ARMS_CHEST, False),
    (1,  ARMS_CHEST, False),
    (0,  ARMS_CHEST, False),
]

frames = [make_frame(b, a, s, i) for i, (b, a, s) in enumerate(SEQ)]

frames[0].save(
    "/mnt/user-data/outputs/yesus_kalah.gif",
    save_all=True,
    append_images=frames[1:],
    duration=[220, 220, 220, 220, 220, 220, 220, 220],
    loop=0,
    optimize=False,
    disposal=2,
)

frames[0].save("/home/claude/preview_jesus_kalah.png")
print("selesai")
