<style>
    @page {
        margin: 32px 36px 48px 36px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10pt;
        color: #1f2937;
        line-height: 1.45;
    }

    .report-header {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        border-bottom: 3px solid #10b981;
        padding-bottom: 12px;
    }

    .report-header td {
        vertical-align: middle;
    }

    .brand-logo {
        width: 46px;
        height: 46px;
        background-color: #10b981;
        color: #ffffff;
        font-size: 20pt;
        font-weight: bold;
        text-align: center;
        line-height: 46px;
    }

    .brand-name {
        font-size: 17pt;
        font-weight: bold;
        color: #5c4a32;
    }

    .brand-tagline {
        font-size: 7.5pt;
        color: #059669;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: bold;
    }

    .report-title {
        font-size: 13pt;
        font-weight: bold;
        color: #111827;
        margin-top: 3px;
    }

    .report-meta {
        text-align: right;
        font-size: 9pt;
        color: #6b7280;
    }

    .report-meta strong {
        color: #374151;
        font-size: 9.5pt;
    }

    .period-pill {
        margin-top: 6px;
        padding: 4px 10px;
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
        font-size: 8.5pt;
        font-weight: bold;
    }

    .section-title {
        font-size: 11pt;
        font-weight: bold;
        color: #5c4a32;
        margin: 16px 0 10px 0;
        padding: 6px 0 6px 8px;
        border-left: 4px solid #10b981;
        background-color: #f9fafb;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 14px;
    }

    .summary-table td {
        width: 25%;
        padding: 4px;
        vertical-align: top;
    }

    .stat-box {
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        background-color: #f9fafb;
        min-height: 52px;
    }

    .stat-box--green {
        background-color: #ecfdf5;
        border-color: #a7f3d0;
    }

    .stat-box--blue {
        background-color: #eff6ff;
        border-color: #bfdbfe;
    }

    .stat-box--gold {
        background-color: #fffbeb;
        border-color: #fde68a;
    }

    .stat-label {
        font-size: 7.5pt;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 3px;
    }

    .stat-value {
        font-size: 12pt;
        font-weight: bold;
        color: #111827;
    }

    .stat-value.green {
        color: #047857;
    }

    .stat-value.red {
        color: #b91c1c;
    }

    .profit-banner {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }

    .profit-banner td {
        background-color: #10b981;
        color: #ffffff;
        text-align: center;
        padding: 12px 16px;
    }

    .profit-banner .label {
        font-size: 8.5pt;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .profit-banner .value {
        font-size: 15pt;
        font-weight: bold;
        margin-top: 3px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
        font-size: 9pt;
    }

    .data-table thead th {
        background-color: #10b981;
        color: #ffffff;
        font-weight: bold;
        text-align: left;
        padding: 8px 9px;
        font-size: 8pt;
        text-transform: uppercase;
    }

    .data-table tbody td {
        padding: 7px 9px;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-table tbody tr.alt td {
        background-color: #f9fafb;
    }

    .data-table tfoot td {
        padding: 9px;
        background-color: #ecfdf5;
        font-weight: bold;
        border-top: 2px solid #10b981;
    }

    .text-right {
        text-align: right;
    }

    .rank {
        color: #10b981;
        font-weight: bold;
    }

    .product-name {
        font-weight: bold;
    }

    .muted {
        color: #6b7280;
    }

    .empty-msg {
        text-align: center;
        padding: 20px;
        color: #9ca3af;
        font-style: italic;
    }

    .report-footer-meta {
        width: 100%;
        margin-top: 8px;
        border-collapse: collapse;
        font-size: 8pt;
        color: #9ca3af;
    }

    .disclaimer {
        margin-top: 12px;
        padding: 10px;
        background-color: #f3f4f6;
        font-size: 8pt;
        color: #6b7280;
        text-align: center;
        border: 1px solid #e5e7eb;
    }
</style>
