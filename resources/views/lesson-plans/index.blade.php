@extends('layouts.app')
@section('page-content')
    {{-- Page Header --}}
    <div>
        <h1 class="text-xl font-bold text-gray-800">Lesson Plans — Export</h1>
        <div class="bread-crumb mt-1">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <span class="text-gray-500">Lesson Plans</span>
        </div>
    </div>


    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --accent: #1b4332;
            --accent-mid: #2d6a4f;
            --accent-light: #40916c;
            --accent-pale: #d8f3dc;
            --accent-faint: #f0faf4;
            --ink: #1a1a2e;
            --ink2: #8a8aa5;
            --ink3: #888;
            --surface: #fff;
            --surface2: #fcfcfc;
            --border: #e2e0da;
            --border2: #ccc9c0;
            --r: 8px;
            --rl: 12px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f4f3ef;
            color: var(--ink);
            min-height: 100vh;
        }

        .page-head {
            margin-bottom: 2rem;
        }

        .eyebrow {
            font-size: 10px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--accent-light);
            font-weight: 600;
            margin-bottom: .5rem;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 500;
            color: var(--ink);
            line-height: 1.2;
        }

        .page-title i {
            font-style: italic;
            color: var(--accent-mid);
        }

        .page-sub {
            font-size: .85rem;
            color: var(--ink2);
            margin-top: .4rem;
            font-weight: 300;
        }

        .section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--rl);
            padding: 1.4rem 1.5rem;
            margin-bottom: 1rem;
        }

        .sec-label {
            font-size: 10px;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--ink3);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .grade-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
        }

        .g-btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .8rem;
            font-weight: 500;
            border: 1px solid var(--border2);
            border-radius: var(--r);
            padding: .55rem .3rem;
            text-align: center;
            cursor: pointer;
            color: var(--ink2);
            background: var(--surface2);
            transition: all .15s;
            user-select: none;
        }

        .g-btn:hover {
            border-color: var(--accent-light);
            color: var(--accent-mid);
            background: var(--accent-faint);
        }

        .g-btn.sel {
            background: var(--accent);
            color: #d8f3dc;
            border-color: var(--accent);
        }

        .subj-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 8px;
        }

        .s-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            border: 1px solid var(--border2);
            border-radius: var(--r);
            padding: .65rem .9rem;
            cursor: pointer;
            font-size: .82rem;
            color: var(--ink2);
            background: var(--surface2);
            transition: all .15s;
            user-select: none;
        }

        .s-chip .check {
            width: 15px;
            height: 15px;
            border: 1.5px solid var(--border2);
            border-radius: 4px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .s-chip .check svg {
            display: none;
            width: 9px;
            height: 9px;
        }

        .s-chip:hover {
            border-color: var(--accent-light);
            background: var(--accent-faint);
        }

        .s-chip.sel {
            background: var(--accent-pale);
            border-color: var(--accent-light);
            color: var(--accent);
        }

        .s-chip.sel .check {
            background: var(--accent-mid);
            border-color: var(--accent-mid);
        }

        .s-chip.sel .check svg {
            display: block;
        }

        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .field-label {
            font-size: 10px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ink3);
            font-weight: 600;
        }

        .field input[type=number] {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .85rem;
            color: var(--ink);
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: var(--r);
            padding: .6rem .85rem;
            outline: none;
            width: 100%;
            transition: border .15s;
        }

        .field input:focus {
            border-color: var(--accent-light);
        }

        .quick-btns {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .q-btn {
            font-size: .75rem;
            font-weight: 500;
            color: var(--accent-mid);
            background: var(--accent-faint);
            border: 1px solid var(--accent-pale);
            border-radius: 20px;
            padding: .32rem .8rem;
            cursor: pointer;
            transition: all .15s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .q-btn:hover {
            background: var(--accent-pale);
        }

        .hint {
            font-size: .75rem;
            color: var(--ink3);
            margin-top: .9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .summary {
            background: var(--accent);
            border-radius: var(--rl);
            padding: 1.2rem 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.2rem;
        }

        .sum-item {
            flex: 1;
            min-width: 90px;
        }

        .sum-lbl {
            font-size: 9px;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(216, 243, 220, .6);
            font-weight: 600;
            margin-bottom: 3px;
        }

        .sum-val {
            font-size: .9rem;
            font-weight: 500;
            color: #d8f3dc;
        }

        .print-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            background: #d97706;
            color: #fff;
            border: none;
            border-radius: var(--r);
            padding: .75rem 1.4rem;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background .15s;
            white-space: nowrap;
            margin-left: auto;
        }

        .print-btn:hover {
            background: #b45309;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-box {
            background: #fff;
            border-radius: 16px;
            padding: 2rem 2.5rem;
            text-align: center;
        }

        .spinner {
            width: 38px;
            height: 38px;
            border: 3px solid var(--accent-pale);
            border-top-color: var(--accent-mid);
            border-radius: 50%;
            animation: spin .65s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }
    </style>

    <body>

        <div class="loading-overlay" id="loadingOverlay" style="display:none;">
            <div class="loading-box">
                <div class="spinner"></div>
                <p style="color:var(--accent-mid);font-weight:500;font-size:.9rem;">Generating PDF…</p>
                <p style="color:var(--ink3);font-size:.78rem;margin-top:.3rem;">This may take a moment</p>
            </div>
        </div>

        <div class="w-full mx-auto p:0 md:p-6">
            <div class="page-head">
                <h2>Grade {{ $grade->grade_no }}</h2>
                <p class="page-sub">Select subjects and a lesson number range — then export a print-ready PDF</p>
            </div>

            @if ($errors->any())
                <div
                    style="background:#fee2e2;border:1px solid #fecaca;border-radius:var(--r);padding:.85rem 1.1rem;margin-bottom:1rem;font-size:.85rem;color:#991b1b;">
                    {{ $errors->first() }}
                </div>
            @endif

            <input type="hidden" id="selectedGradeName" value="{{ $grade->name }}">
            <input type="hidden" id="selectedGradeId" value="{{ $grade->id }}">

            {{-- Step 2: Subjects --}}
            <div class="section">
                <div class="sec-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    Step 1 — Select subjects
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                    @foreach ($subjects as $subject)
                        <div class="s-chip" data-id="{{ $subject->id }}" data-name="{{ $subject->name }}">
                            <div class="check">
                                <svg viewBox="0 0 12 12" fill="none" stroke="#fff" stroke-width="2.5">
                                    <polyline points="2,6 5,9 10,3" />
                                </svg>
                            </div>
                            <span>{{ $subject->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Step 2: Lesson range --}}
            <div class="section">
                <div class="sec-label">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    Step 2 — Lesson number range
                </div>
                <div class="date-row">
                    <div class="field">
                        <div class="field-label">From</div>
                        <input type="number" id="lessonFrom" min="1" placeholder="e.g. 1">
                    </div>
                    <div class="field">
                        <div class="field-label">To</div>
                        <input type="number" id="lessonTo" min="1" placeholder="e.g. 20">
                    </div>
                </div>
                <div class="quick-btns">
                    <button class="q-btn" onclick="setRange(1,10)">Lessons 1–10</button>
                    <button class="q-btn" onclick="setRange(1,20)">Lessons 1–20</button>
                    <button class="q-btn" onclick="setRange(1,40)">Lessons 1–40</button>
                    <button class="q-btn" onclick="setRange(11,20)">Lessons 11–20</button>
                    <button class="q-btn" onclick="setRange(21,40)">Lessons 21–40</button>
                </div>
            </div>

            {{-- Summary bar --}}
            <div class="summary" id="summaryBar" style="display:none;">
                <div class="sum-item">
                    <div class="sum-lbl">Grade</div>
                    <div class="sum-val" id="sGrade">—</div>
                </div>
                <div class="sum-item">
                    <div class="sum-lbl">Subjects</div>
                    <div class="sum-val" id="sSubjects">—</div>
                </div>
                <div class="sum-item">
                    <div class="sum-lbl">Lesson range</div>
                    <div class="sum-val" id="sRange">—</div>
                </div>
                <div class="sum-item">
                    <div class="sum-lbl">Est. records</div>
                    <div class="sum-val" id="sCount">—</div>
                </div>
                <button class="print-btn" id="printBtn" onclick="exportPdf()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect x="6" y="14" width="12" height="8" />
                    </svg>
                    Export PDF
                </button>
            </div>

        </div>

        <script>
            let selGradeId = document.getElementById('selectedGradeId').value,
                selGradeName = document.getElementById('selectedGradeName').value;

            let selSubjIds = new Set(),
                selSubjNames = new Set();
            let lFrom = '',
                lTo = '';

            document.querySelectorAll('.g-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.g-btn').forEach(b => b.classList.remove('sel'));
                    btn.classList.add('sel');
                    // document.getElementById('selectedGradeId').value = selGradeId;
                    document.getElementById('selectedGradeName').value = selGradeName;
                    update();
                });
            });

            document.querySelectorAll('.s-chip').forEach(chip => {
                chip.addEventListener('click', () => {
                    chip.classList.toggle('sel');
                    const id = chip.dataset.id,
                        name = chip.dataset.name;
                    if (chip.classList.contains('sel')) {
                        selSubjIds.add(id);
                        selSubjNames.add(name);
                    } else {
                        selSubjIds.delete(id);
                        selSubjNames.delete(name);
                    }
                    update();
                });
            });

            document.getElementById('lessonFrom').addEventListener('input', e => {
                lFrom = e.target.value;
                update();
            });
            document.getElementById('lessonTo').addEventListener('input', e => {
                lTo = e.target.value;
                update();
            });

            function setRange(a, b) {
                lFrom = String(a);
                lTo = String(b);
                document.getElementById('lessonFrom').value = a;
                document.getElementById('lessonTo').value = b;
                update();
            }

            function update() {
                const ok = selSubjIds.size > 0 && lFrom && lTo && parseInt(lTo) >= parseInt(lFrom);
                const bar = document.getElementById('summaryBar');
                if (!ok) {
                    bar.style.display = 'none';
                    return;
                }
                bar.style.display = 'flex';
                const cnt = (parseInt(lTo) - parseInt(lFrom) + 1) * selSubjIds.size;
                document.getElementById('sGrade').textContent = selGradeName;
                const names = [...selSubjNames];
                document.getElementById('sSubjects').textContent = names.length <= 2 ? names.join(', ') : names.slice(0, 2)
                    .join(', ') + ` +${names.length-2}`;
                document.getElementById('sRange').textContent = `#${lFrom} – #${lTo}`;
                document.getElementById('sCount').textContent = cnt;
            }

            function exportPdf() {
                if (!selGradeId || !selSubjIds.size || !lFrom || !lTo) return;
                document.getElementById('loadingOverlay').style.display = 'flex';
                const url =
                    `{{ route('lesson-plans.pdf') }}?grade_id=${selGradeId}&subject_ids=${[...selSubjIds].join(',')}&from=${lFrom}&to=${lTo}`;
                const w = window.open(url, '_blank');
                if (w) w.focus();
                setTimeout(() => document.getElementById('loadingOverlay').style.display = 'none', 2500);
            }
        </script>
    @endsection
