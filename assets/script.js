/* CRM Gruppe 4 — script.js */

// Live-søk i tabellen mens du skriver
function myFunction() {
  const input = document.getElementById("myInput");
  if (!input) return;

  const filter = input.value.toUpperCase();
  document.querySelectorAll("tbody tr").forEach(row => {
    const match = row.textContent.toUpperCase().includes(filter);
    row.style.display = match ? "" : "none";
  });
}

document.addEventListener("DOMContentLoaded", () => {

  // Stats-bar
  const topbar = document.querySelector(".topbar");
  if (!topbar) return;

  const rows     = document.querySelectorAll("tbody tr:not(.tom-rad)");
  const aktiv    = document.querySelectorAll(".badge-aktiv").length;
  const inaktiv  = document.querySelectorAll(".badge-inaktiv").length;
  const total    = rows.length;

  // Kunder-siden har badges, kontaktpersoner-siden har ikke
  const erKunder      = aktiv + inaktiv > 0;
  const erKontakter   = total > 0 && !erKunder;

  if (!erKunder && !erKontakter) return;

  const stats = erKunder
    ? [
        ["Totalt kunder", total,   "#1f2937"],
        ["Aktive",         aktiv,   "#10b981"],
        ["Inaktive",       inaktiv, "#ef4444"],
      ]
    : [
        ["Kontaktpersoner", total, "#1f2937"],
      ];

  // Tell unike firmaer på kontaktpersoner-siden
  if (erKontakter) {
    const firmaer = new Set();
    rows.forEach(row => {
      const firma = row.querySelectorAll("td")[1]?.textContent.trim();
      if (firma) firmaer.add(firma);
    });
    stats.push(["Tilknyttede firmaer", firmaer.size, "#3b82f6"]);
  }

  const bar = document.createElement("div");
  bar.style.cssText = "display:flex;gap:16px;margin-bottom:22px;flex-wrap:wrap;";
  bar.innerHTML = stats.map(([lbl, num, color]) => `
    <div style="background:#fff;border-radius:10px;padding:14px 24px;
                box-shadow:0 2px 6px rgba(0,0,0,0.06);min-width:110px;text-align:center;">
      <span style="display:block;font-size:28px;font-weight:700;color:${color}">${num}</span>
      <span style="display:block;font-size:12px;color:#6b7280;margin-top:2px">${lbl}</span>
    </div>`).join("");

  topbar.insertAdjacentElement("afterend", bar);
});
