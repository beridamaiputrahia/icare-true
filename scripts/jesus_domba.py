"""
Pixel-art GIF: figur Yesus (Gembala Baik) berdiri bersama sekumpulan
domba kecil di kiri-kanan — dipakai untuk status "selesai" mode Solo
(tidak ada menang/kalah/seri karena tidak melawan siapa pun).
Struktur sama seperti versi lain: canvas lebar (figur Yesus di tengah,
domba di sisi kiri-kanan) -> grid manual -> outline otomatis -> upscale.
"""

from PIL import Image

W, H = 56, 35
SCALE = 10
DY = 4
CX = 12  # offset horizontal figur Yesus supaya di tengah canvas lebar

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
    # domba
    'F': (250, 247, 240),   # bulu domba
    'f': (219, 214, 202),   # bayangan bulu
    'D': (74, 58, 46),      # wajah/kaki domba
}

OUTLINE = (34, 24, 44)
BG = (24, 20, 46)
FLOOR = (40, 33, 68)
SHADOW = (17, 14, 32)
GLOW = (70, 62, 96)
GLOW2 = (52, 45, 78)

BASE = [
    ".............KKKKKK.............",  # 0  halo (busur atas)
    "...........KK......KK...........",  # 1  halo (sisi)
    ".............KKKKKK.............",  # 2  halo (busur bawah)
    "................................",  # 3
    "..........hhhhhhhhhhhh..........",  # 4  rambut
    "..........hhSSSSSSSShh..........",  # 5
    "..........hSSSSSSSSSSh..........",  # 6
    "..........hSSESSSSESSh..........",  # 7  mata
    "..........hScSSssSScSh..........",  # 8  pipi + hidung
    "..........hSbbbbbbbbSh..........",  # 9  kumis
    "..........hbbbOOOObbbh..........",  # 10 senyum
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


# Satu tangan turun menggendong/menuntun domba kecil di sisi kiri
_left_down = [
    (8, 14, 'V'), (7, 14, 'W'), (7, 15, 'W'), (6, 15, 'W'),
    (6, 16, 'W'), (6, 17, 'w'), (7, 17, 'w'),
    (5, 18, 'S'), (6, 18, 'S'), (5, 19, 'S'), (6, 19, 'S'),
]
ARMS_DOWN = _left_down + mirror(_left_down)


def sheep(ox, oy, facing_right=True):
    """Domba kecil sederhana (badan bulat + kepala + 2 kaki)."""
    body = [
        (1, 0, 'F'), (2, 0, 'F'), (3, 0, 'F'),
        (0, 1, 'F'), (1, 1, 'F'), (2, 1, 'F'), (3, 1, 'F'), (4, 1, 'F'),
        (0, 2, 'F'), (1, 2, 'f'), (2, 2, 'F'), (3, 2, 'f'), (4, 2, 'F'),
        (1, 3, 'F'), (2, 3, 'F'), (3, 3, 'F'),
    ]
    head = [(4, 1, 'D'), (5, 1, 'D'), (5, 2, 'D')] if facing_right else [(0, 1, 'D'), (-1, 1, 'D'), (-1, 2, 'D')]
    legs = [(1, 4, 'D'), (3, 4, 'D')]
    px = [(ox + x, oy + y, ch) for (x, y, ch) in body + legs]
    px += [(ox + x, oy + y, ch) for (x, y, ch) in head]
    return px


SHEEP_LEFT = sheep(1, 24, facing_right=True) + sheep(5, 27, facing_right=True)
SHEEP_RIGHT = sheep(46, 27, facing_right=False) + sheep(50, 24, facing_right=False)

MOTES = [
    (3, 1, (255, 228, 140)), (14, 6, (240, 226, 196)), (24, 3, (255, 228, 140)),
    (36, 9, (198, 214, 240)), (46, 5, (255, 228, 140)), (52, 10, (240, 226, 196)),
    (9, 13, (198, 214, 240)), (40, 15, (255, 228, 140)), (28, 19, (240, 226, 196)),
    (2, 21, (255, 228, 140)), (18, 24, (198, 214, 240)), (49, 22, (240, 226, 196)),
]


def build_sprite(bounce, arms):
    layer = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    px = layer.load()
    for y, row in enumerate(BASE):
        for x, ch in enumerate(row):
            if ch != '.':
                xx = x + CX
                yy = y + DY + bounce
                if 0 <= xx < W and 0 <= yy < H:
                    px[xx, yy] = PALETTE[ch] + (255,)
    for (x, y, ch) in arms:
        xx = x + CX
        yy = y + DY + bounce
        if 0 <= xx < W and 0 <= yy < H:
            px[xx, yy] = PALETTE[ch] + (255,)
    for (x, y, ch) in SHEEP_LEFT + SHEEP_RIGHT:
        yy = y + bounce
        if 0 <= x < W and 0 <= yy < H:
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


def make_frame(bounce, arms, tick):
    bg = Image.new("RGBA", (W, H), BG + (255,))
    bpx = bg.load()

    cx, cy = CX + 15.5, 9 + DY + bounce
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
        y = (y0 + tick * 2) % 32
        bpx[x % W, y] = col + (255,)

    shrink = abs(bounce)
    x0, x1 = CX + 9 + shrink, CX + 22 - shrink
    for x in range(x0, x1 + 1):
        bpx[x, 32] = SHADOW + (255,)

    sprite = add_outline(build_sprite(bounce, arms))
    bg.alpha_composite(sprite)

    return bg.convert("RGB").resize((W * SCALE, H * SCALE), Image.NEAREST)


# Napas pelan naik-turun, tangan turun menemani domba — tenang & hangat,
# suasana "Gembala Baik", bukan perayaan ramai seperti versi bersorak.
SEQ = [
    (0,  ARMS_DOWN),
    (0,  ARMS_DOWN),
    (-1, ARMS_DOWN),
    (-1, ARMS_DOWN),
    (0,  ARMS_DOWN),
    (0,  ARMS_DOWN),
    (1,  ARMS_DOWN),
    (0,  ARMS_DOWN),
]

frames = [make_frame(b, a, i) for i, (b, a) in enumerate(SEQ)]

frames[0].save(
    "/mnt/user-data/outputs/yesus_domba.gif",
    save_all=True,
    append_images=frames[1:],
    duration=[220, 220, 220, 220, 220, 220, 220, 220],
    loop=0,
    optimize=False,
    disposal=2,
)

frames[0].save("/home/claude/preview_jesus_domba.png")
print("selesai")
