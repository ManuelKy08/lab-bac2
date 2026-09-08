#!/usr/bin/env bash
# ============================================================
# LAB BAC #2 - Peluncur (Tugas Kuliah) — kikikokok
#   ./jalankan.sh          # import DB + nyalakan server :8093
#   ./jalankan.sh stop     # matikan
# ============================================================
set -e
DIR="$(cd "$(dirname "$0")" && pwd)"
PORT=8093

if [ "$1" = "stop" ]; then
  fuser -k "${PORT}/tcp" 2>/dev/null || true
  pkill -f "php -S 127.0.0.1:${PORT}" 2>/dev/null || true
  echo "[lab-bac2] server mati."
  exit 0
fi

echo "[lab-bac2] import database lab_bac2 ..."
mariadb -uroot < "$DIR/database/lab_bac2.sql"

echo "[lab-bac2] jalankan http://127.0.0.1:$PORT ..."
nohup setsid php -S "127.0.0.1:$PORT" -t "$DIR" > "$DIR/php-server.log" 2>&1 < /dev/null &
sleep 2
curl -s -o /dev/null -w "[lab-bac2] cek server: HTTP %{http_code}\n" "http://127.0.0.1:$PORT/index.php"
echo "[lab-bac2] selesai. Buka http://127.0.0.1:$PORT"