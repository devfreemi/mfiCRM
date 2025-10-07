<?php
// app/Views/market_recipients_dropdown.php

// Normalize data to $marketsGrouped: [ groupId => [ row, row, ... ], ... ]
$marketsGrouped = [];

// Priority: if controller provided grouped $markets, use it
if (! empty($markets) && is_array($markets)) {
    $marketsGrouped = $markets;
} elseif (! empty($recipients) && is_array($recipients)) {
    // If controller passed a flat $recipients from the SQL view, group them by groupId
    foreach ($recipients as $r) {
        // Normalize possible column names
        $key = $r['groupId'];
        // ensure label is present
        if (! isset($r['groupName'])) {
            $r['groupName'] = $r['groupName'];
        }
        if (! isset($marketsGrouped[$key])) {
            $marketsGrouped[$key] = [];
        }
        $marketsGrouped[$key][] = $r;
    }
}

// Prepare a conservative label for dropdown (groupName or label or formatted key)
function groupName_from_row($row, $key)
{
    if (! empty($row['groupName'])) return $row['groupName'];
    // if (! empty($row['label'])) return $row['label'];
    // fallback: prettify key
    return ucwords(str_replace('_', ' ', $key));
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Markets & Recipients</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .market-table {
            max-height: 420px;
            overflow: auto;
        }

        .small-muted {
            font-size: .9rem;
            color: #6c757d;
        }

        .blurred-text {

            filter: blur(3px);
            /* Adjust the pixel value for more or less blur */
            -webkit-filter: blur(5px);
            /* For Safari compatibility */
        }
    </style>
</head>

<body class="bg-light">
    <main class="container py-4">
        <h1 class="mb-3">Markets & Recipients</h1>

        <section aria-labelledby="market-filter" class="mb-4">
            <div class="row g-2 align-items-center">
                <label id="market-filter" for="marketSelect" class="col-auto col-form-label">Choose market</label>
                <div class="col-auto">
                    <select id="marketSelect" class="form-select" aria-label="Select market">
                        <option value="">-- Select market --</option>
                        <?php foreach ($marketsGrouped as $mkey => $rows): ?>
                            <?php $lbl = groupName_from_row($rows[0], $mkey); ?>
                            <option value="<?= esc($mkey) ?>"><?= esc($lbl) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-auto">
                    <button id="selectAllBtn" type="button" class="btn btn-sm btn-outline-secondary">Select All</button>
                    <button id="clearAllBtn" type="button" class="btn btn-sm btn-outline-secondary">Clear All</button>


                </div>
                <div class="row mt-4">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Project ID" aria-label="First name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Template name" aria-label="Last name">
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-primary w-100" id="sendRcs">Submit</button>
                    </div>
                </div>
                <div class="col-auto ms-auto">
                    <label class="visually-hidden" for="searchInput">Search</label>
                    <input id="searchInput" type="search" class="form-control form-control-sm" placeholder="Search name or phone">
                </div>
            </div>
            <p class="small-muted mt-2">Recipients shown are fetched from the SQL view and grouped by market.</p>
        </section>

        <section aria-labelledby="recipients-heading">
            <h2 id="recipients-heading" class="h6 visually-hidden">Recipients Table</h2>

            <div class="table-responsive market-table">
                <table class="table table-sm table-striped align-middle" role="table" aria-describedby="recipients-heading">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width:40px"><span class="visually-hidden">Select</span></th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Market</th>
                        </tr>
                    </thead>
                    <tbody id="recipientsTbody">
                        <!-- JS will populate -->
                    </tbody>
                </table>
            </div>
        </section>

        <section aria-labelledby="rcs_info-heading" class="mt-4">
            <!--        <h2 id="rcs_info-heading" class="h6">About RCS</h2>-->
            <!--        <p class="small-muted">-->
            <!--            RCS (Rich Communication Services) is an advanced messaging protocol that enhances traditional SMS by enabling features like high-resolution images, read receipts, typing indicators, and interactive elements. It provides a more engaging and interactive experience for users, similar to popular messaging apps. RCS is designed to work across different devices and carriers, making it a versatile option for modern communication.-->
            <!--        </p>-->
            <!-- <div class="row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Project ID" aria-label="First name">
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Template name" aria-label="Last name">
                </div>

            </div> -->
        </section>
    </main>

    <!-- Pass marketsGrouped to JS safely -->
    <script>
        const markets = <?= json_encode($marketsGrouped, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    </script>

    <script>
        (function() {
            const marketSelect = document.getElementById('marketSelect');
            const recipientsTbody = document.getElementById('recipientsTbody');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const clearAllBtn = document.getElementById('clearAllBtn');
            const searchInput = document.getElementById('searchInput');

            // Helper to sanitize text for insertion
            function escapeHtml(s) {
                if (s === null || s === undefined) return '';
                return String(s)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // Render recipients for a given market key (marketKey)
            function renderRecipients(marketKey, filter = '') {
                recipientsTbody.innerHTML = '';
                if (!marketKey || !markets[marketKey]) return;
                const rows = markets[marketKey];

                const q = filter.trim().toLowerCase();

                rows.forEach((r, idx) => {
                    const name = r.name ?? '';
                    const phone = r.mobile ?? '';
                    const marketLabel = r.groupName ?? r.label ?? (marketKey.replace(/_/g, ' '));

                    // filter by name or phone
                    if (q) {
                        if (!(name.toLowerCase().includes(q) || phone.toLowerCase().includes(q))) {
                            return; // skip this row
                        }
                    }

                    const tr = document.createElement('tr');

                    const tdCheck = document.createElement('td');
                    const chk = document.createElement('input');
                    chk.type = 'checkbox';
                    chk.className = 'form-check-input recipient-checkbox';
                    chk.dataset.phone = phone;
                    chk.checked = true;
                    tdCheck.appendChild(chk);

                    const tdName = document.createElement('td');
                    tdName.innerHTML = escapeHtml(name);

                    const tdPhone = document.createElement('td');
                    tdPhone.classList.add('blurred-text');
                    tdPhone.innerHTML = escapeHtml(phone);

                    const tdMarket = document.createElement('td');
                    tdMarket.innerHTML = escapeHtml(marketLabel);

                    tr.appendChild(tdCheck);
                    tr.appendChild(tdName);
                    tr.appendChild(tdPhone);
                    tr.appendChild(tdMarket);

                    recipientsTbody.appendChild(tr);
                });

                // If nothing shown, show a placeholder row
                if (!recipientsTbody.children.length) {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.colSpan = 4;
                    td.className = 'text-muted small p-3';
                    td.textContent = 'No recipients found for this market / filter.';
                    tr.appendChild(td);
                    recipientsTbody.appendChild(tr);
                }
            }

            // Event listeners
            marketSelect.addEventListener('change', () => {
                renderRecipients(marketSelect.value, searchInput.value);
            });

            selectAllBtn.addEventListener('click', () => {
                document.querySelectorAll('.recipient-checkbox').forEach(cb => cb.checked = true);
            });

            clearAllBtn.addEventListener('click', () => {
                document.querySelectorAll('.recipient-checkbox').forEach(cb => cb.checked = false);
            });

            searchInput.addEventListener('input', () => {
                renderRecipients(marketSelect.value, searchInput.value);
            });

            // Auto-select first market if present
            (function autoSelectFirst() {
                if (marketSelect.options.length > 1) {
                    marketSelect.selectedIndex = 1;
                    renderRecipients(marketSelect.value, '');
                } else {
                    // no markets: show nothing
                    recipientsTbody.innerHTML = '<tr><td colspan="4" class="text-muted small p-3">No markets available.</td></tr>';
                }
            })();
        })();

        document.getElementById("sendRcs").addEventListener("click", () => {
            const projectId = document.querySelector('input[placeholder="Project ID"]').value.trim();
            const templateName = document.querySelector('input[placeholder="Template name"]').value.trim();
            const selectedPhones = Array.from(document.querySelectorAll('.recipient-checkbox:checked'))
                .map(cb => cb.dataset.phone)
                .filter(p => p);

            if (!projectId) {
                console.error("Please enter a Project ID.");
                return;
            }
            if (!templateName) {
                console.error("Please enter a Template name.");
                return;
            }
            if (selectedPhones.length === 0) {
                console.error("Please select at least one recipient.");
                return;
            }

            // For demo, just log the data
            console.log("Sending RCS with data:", {
                projectId,
                templateName,
                recipients: selectedPhones
            });

            fetch("https://otp.retailpe.in/api/rcs/send", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        project_id: projectId,
                        template_name: templateName,
                        template_namespace: templateName, // replace with actual namespace
                        phone_numbers: selectedPhones
                    })
                }).then(response => response.json())
                .then(data => {
                    console.log("RCS API response:", data);
                    // alert(`RCS send request completed. Check console for response.`);
                })
                .catch(err => {
                    console.error("Error sending RCS:", err);
                    // alert("Error sending RCS. Check console for details.");
                });

            // alert(`RCS send initiated for ${selectedPhones.length} recipients.`);
        })
    </script>
</body>

</html>