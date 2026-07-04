while read f; do
  fn=${f##*/}
  fn=$(echo "$fn" | tr -d '\r')
  [ -f "/var/www/html/admin/images/$fn" ] || echo "MISSING: $fn"
done < /tmp/images_list.txt