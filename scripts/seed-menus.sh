#!/usr/bin/env bash
#
# Seed the Primary + Footer navigation menus for the Elite Sports Academy
# theme and assign them to their theme locations. Idempotent: existing menus
# of the same name are deleted and rebuilt.
#
set -euo pipefail

cd /var/www/elitesports.local

HOME_URL="$(wp option get home 2>/dev/null)"

wp() {
  command wp "$@" 2>/dev/null
}

reset_menu() {
  local name="$1"
  if wp menu list --fields=name --format=csv | grep -qx "$name"; then
    wp menu delete "$name" >/dev/null || true
  fi
  wp menu create "$name" >/dev/null
}

add_item() {
  # add_item <menu> <title> <link> [parent_id] [target]
  local menu="$1" title="$2" link="$3" parent="${4:-0}" target="${5:-}"
  local args=(menu item add-custom "$menu" "$title" "$link" --porcelain)
  if [[ "$parent" != "0" ]]; then
    args+=(--parent-id="$parent")
  fi
  if [[ -n "$target" ]]; then
    args+=(--target="$target")
  fi
  wp "${args[@]}"
}

# --------------------------------------------------------------------------
# Primary navigation
# --------------------------------------------------------------------------
reset_menu "Primary"

SCHOOL_INFO=$(add_item "Primary" "School Info" "#")
add_item "Primary" "Why Elite?" "${HOME_URL}/#model" "$SCHOOL_INFO" >/dev/null
add_item "Primary" "Calendars" "https://elitesportsacademyaz.com/calendars/" "$SCHOOL_INFO" "_blank" >/dev/null
add_item "Primary" "Academics" "${HOME_URL}/academics" >/dev/null
add_item "Primary" "Training" "${HOME_URL}/#training" >/dev/null
add_item "Primary" "Campus" "${HOME_URL}/#campus" >/dev/null
add_item "Primary" "Coaches" "${HOME_URL}/coaches" >/dev/null
add_item "Primary" "FAQ" "${HOME_URL}/#faq" >/dev/null
add_item "Primary" "Contact" "${HOME_URL}/#contact" >/dev/null

wp menu location assign "Primary" primary_navigation >/dev/null

# --------------------------------------------------------------------------
# Footer navigation (top-level items = columns)
# --------------------------------------------------------------------------
reset_menu "Footer"

HOME_COL=$(add_item "Footer" "Home" "#")
add_item "Footer" "Why Elite?" "${HOME_URL}/#model" "$HOME_COL" >/dev/null
add_item "Footer" "Academics" "${HOME_URL}/#academics" "$HOME_COL" >/dev/null
add_item "Footer" "Training Model" "${HOME_URL}/#training" "$HOME_COL" >/dev/null
add_item "Footer" "Campus" "${HOME_URL}/#campus" "$HOME_COL" >/dev/null
add_item "Footer" "Character" "${HOME_URL}/#character" "$HOME_COL" >/dev/null
add_item "Footer" "What Comes Next" "${HOME_URL}/#future" "$HOME_COL" >/dev/null

PAGES_COL=$(add_item "Footer" "Pages" "#")
add_item "Footer" "Academic Details" "${HOME_URL}/academics" "$PAGES_COL" >/dev/null
add_item "Footer" "Coaches" "${HOME_URL}/coaches" "$PAGES_COL" >/dev/null
add_item "Footer" "Tuition Details" "${HOME_URL}/tuition" "$PAGES_COL" >/dev/null
add_item "Footer" "Financial Information" "${HOME_URL}/tuition#financial-assistance" "$PAGES_COL" >/dev/null

ADMISSIONS_COL=$(add_item "Footer" "Admissions" "#")
add_item "Footer" "Apply Now" "https://heritageacademy.schoolmint.net/" "$ADMISSIONS_COL" "_blank" >/dev/null
add_item "Footer" "Schedule a Tour" "https://docs.google.com/forms/d/e/1FAIpQLSdagnRNZfXdf5yl_XXAaBS2Cn_DgD7qNzdJElZUtp8ngKnxoA/viewform" "$ADMISSIONS_COL" "_blank" >/dev/null
add_item "Footer" "Free Camps" "${HOME_URL}/#free-camps" "$ADMISSIONS_COL" >/dev/null
add_item "Footer" "FAQ" "${HOME_URL}/#faq" "$ADMISSIONS_COL" >/dev/null

wp menu location assign "Footer" footer_navigation >/dev/null

echo "Menus seeded and assigned."
wp menu list --fields=term_id,name,count,locations --format=table
