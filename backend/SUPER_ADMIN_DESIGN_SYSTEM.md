# 🎨 Paulette Culture Kids: Super Admin Panel Design System
## Production-Grade Premium SaaS Interface

**Design Philosophy:** "The Digital Curator"  
**Level:** Enterprise-tier operational dashboard  
**Target Users:** Super Admins, System Engineers, Content Directors  

---

# TABLE OF CONTENTS
1. [Design System Extension](#design-system-extension)
2. [Navigation Architecture](#navigation-architecture)
3. [Core Layout System](#core-layout-system)
4. [Component Library](#component-library)
5. [Data Tables & Patterns](#data-tables--patterns)
6. [Advanced System Screens](#advanced-system-screens)
7. [Content Management Interfaces](#content-management-interfaces)
8. [Theme Engine Design](#theme-engine-design)
9. [Interaction Patterns](#interaction-patterns)
10. [Complete Screen Specifications](#complete-screen-specifications)

---

# DESIGN SYSTEM EXTENSION

## Color Palette (Complete Reference)

### Primary Gradients
```
EMERALD (Growth/Success)
├── Deep:        #006948
├── Base:        #0f9361
├── Light:       #27d384
└── Lime:        #68dba9
   Gradient: linear-gradient(135deg, #006948 0%, #27d384 100%)
   Usage: Success states, main CTAs, healthy metrics
```

```
AMBER (Warnings/Highlights)
├── Deep:        #904d00
├── Base:        #d67800
├── Light:       #fe932c
└── Pale:        #ffc580
   Gradient: linear-gradient(135deg, #904d00 0%, #fe932c 100%)
   Usage: Warnings, storage, secondary highlights, attention
```

```
VIOLET (System/Special)
├── Deep:        #712ae2
├── Base:        #9d5dff
├── Light:       #d2bbff
└── Pale:        #ede8ff
   Gradient: linear-gradient(135deg, #712ae2 0%, #d2bbff 100%)
   Usage: System processes, AI insights, special categories
```

### Neutral Palette (Surface Scales)
```
SURFACE SCALE (No pure greys)
├── Base:              #faf8ff (Primary surface)
├── Container Low:     #f2f3ff (Section backgrounds)
├── Container Mid:     #e8e8f0 (Card backgrounds, hover states)
├── Container High:    #d9d9e8 (Disabled states)
├── Outline Variant:   #cac9d8 (Subtle dividers @ 15% opacity)
└── On Surface:        #131b2e (Text, dark elements)

SEMANTIC NEUTRALS
├── Error:        #c5192d (Failures, exceptions)
├── Success:      #2d7c2d (Confirmations)
├── Warning:      #cc7c1a (Cautions, pending)
└── Info:         #0066cc (Informational)
```

### Glassmorphism Surfaces
```
GLASS TIER 1 (Cards, dialogs):
  background: rgba(255, 248, 255, 0.8)
  backdrop-filter: blur(24px)
  border: 1px solid rgba(202, 201, 216, 0.15)

GLASS TIER 2 (Floating panels):
  background: rgba(242, 243, 255, 0.85)
  backdrop-filter: blur(32px)
  border: 1px solid rgba(202, 201, 216, 0.20)

GLASS TIER 3 (Modals, overlays):
  background: rgba(19, 27, 46, 0.6)
  backdrop-filter: blur(40px)
  border: 1px solid rgba(255, 255, 255, 0.15)
```

## Typography System (Extended)

### Font Stack
```
HEADLINES (Manrope - Geometric, Bold)
├── display-lg:   48px / 3.5rem / weight-700
├── display-md:   40px / 2.5rem / weight-700
├── headline-lg:  28px / 1.75rem / weight-700
├── headline-md:  24px / 1.5rem / weight-700
└── headline-sm:  20px / 1.25rem / weight-700

BODY (Inter - Legible, Neutral)
├── body-lg:      16px / 1rem / weight-400 (Descriptions)
├── body-md:      14px / 0.875rem / weight-400 (Dashboard data)
├── body-sm:      12px / 0.75rem / weight-400 (Subtext)
└── label-sm:     11px / 0.6875rem / weight-500 (Metadata, row labels)

LINE HEIGHT
├── Tight:        1.2 (Headlines)
├── Normal:       1.5 (Body)
└── Loose:        1.8 (Descriptions with high density)
```

## Spacing System

```
SCALE (Base: 4px)
├── spacing-1:    4px
├── spacing-2:    8px
├── spacing-3:   12px
├── spacing-4:   16px    ← Default card internal spacing
├── spacing-5:   20px    ← Section spacing
├── spacing-6:   24px    ← Container-to-container
├── spacing-8:   32px    ← Major section breaks
└── spacing-10:  40px    ← Full-height separators

APPLICATION RULES
├── Hstack/Vstack: spacing-3 or spacing-4
├── Card padding: spacing-6
├── Section header margin: spacing-8
└── Table cell padding: spacing-4 (vert) × spacing-5 (horiz)
```

## Corner Radius System

```
SCALE
├── sm:           8px   (Input fields)
├── md:          12px   (Small components)
├── lg:          16px   (Most cards)
├── xl:          20px   (Large cards, buttons)
└── 2xl:         24px   (Full containers, hero sections)

APPLICATION
├── Buttons:      xl (20px)
├── Cards:        2xl (24px)
├── Inputs:       md (12px)
├── Tables:       lg (16px)
└── Modals:       2xl (24px)
```

## Shadow System (Tonal Layering)

```
AMBIENT SHADOWS (Soft natural light)
├── sm:     0 2px 8px rgba(19, 27, 46, 0.04)
├── md:     0 4px 16px rgba(19, 27, 46, 0.06)
├── lg:     0 8px 32px rgba(19, 27, 46, 0.08)
└── xl:     0 16px 48px rgba(19, 27, 46, 0.12)

FLOAT SHADOWS (Elevation on hover/lift)
├── lift-sm:  0 4px 16px rgba(19, 27, 46, 0.06)
├── lift-md:  0 8px 32px rgba(19, 27, 46, 0.10)
└── lift-lg:  0 12px 40px rgba(19, 27, 46, 0.14)

APPLICATION RULES
├── Default cards:  shadow-md
├── Hover cards:    shadow-lg + scale(1.02)
├── Floating menus: shadow-xl
└── No borders:     Use shadows for definition only
```

---

# NAVIGATION ARCHITECTURE

## Primary Sidebar (Always Visible)

### Sidebar Structure
```
WIDTH: 240px (Desktop) / 72px (Collapsed)
BACKGROUND: surface_container_low with 15% opacity border-right
TYPOGRAPHY: label-md for labels, headline-sm for grouping

LAYOUT:
┌─────────────────────────────────┐
│  🎨 PAULETTE CULTURE KIDS       │  (Logo + brand, 64px height)
│  SUPER ADMIN PORTAL             │
├─────────────────────────────────┤
│ GLOBAL SEARCH                   │  (Search bar, spacing-4 padding)
├─────────────────────────────────┤
│                                 │
│ ▶ PLATFORM                      │  (Module group, always expanded)
│   ├ 📊 Global Dashboard         │  (With active gradient highlight)
│   ├ 🏢 Organisations            │
│   ├ 👥 Users & Roles            │
│   └ 📋 Tribes & Segments        │
│                                 │
│ CONTENT                         │  (Toggle-able group)
│   ├ 📚 Comics CMS               │
│   ├ 🎵 Songs & Audio            │
│   ├ 📇 Flashcards              │
│   └ 📦 Bundle Builder           │
│                                 │
│ SYSTEM                          │  (Advanced features)
│   ├ ⚡ Sync Monitor             │
│   ├ 📥 Queue Manager            │
│   ├ 🔴 Error Logs               │
│   ├ 💾 Storage Usage            │
│   ├ 📝 Audit Trail              │
│   ├ 🔐 API Tokens               │
│   └ ⚙️  System Health           │
│                                 │
│ THEMING                         │
│   └ 🎨 Theme Engine             │
│                                 │
├─────────────────────────────────┤
│ ❓ Help & Support               │
│ 🔓 Sign Out                     │
└─────────────────────────────────┘
```

### Active State Styling
```
ACTIVE NAVIGATION ITEM:
├── Background: Gradient emerald-to-lime (opacity: 20%)
├── Left border: 3px solid emerald (gradient)
├── Text: emerald_dark (700 weight)
├── Icon: emerald_dark

HOVER STATE (Non-active):
├── Background: surface_container_mid (opacity: 50%)
├── Transition: smooth 200ms

INACTIVE TEXT:
├── Color: on_surface (60% opacity)
└── Icon: on_surface (60% opacity)
```

## Top Navigation Bar (Primary Controls)

```
HEIGHT: 64px
BACKGROUND: Glass tier 2 (rgba(242, 243, 255, 0.85) + blur)
LAYOUT: 
  [Search] [Filters] [Spacing] [Notifications] [User Menu]

SEARCH BAR:
├── Width: 300px (auto-expands on focus)
├── Placeholder: "Search organisations, users, comics..."
├── Icons: Scope selector (orgs only / all scopes)
└── Results: Dropdown with 5 recent + suggestions

NOTIFICATIONS:
├── Bell icon with badge (count of system alerts)
├── On click: Panel slides in from right
├── Content: Real-time sync errors, queue failures, storage alerts

USER MENU:
├── Avatar (initials) + Name
├── On click: Dropdown with settings, sign out
└── Secondary info: Current org, role badge
```

---

# CORE LAYOUT SYSTEM

## Master Grid & Spacing

### Desktop Layout (>1200px)
```
Full Grid (12 columns, 20px gutter):
┌──────────────────────────────────────────────────┐
│ SIDEBAR (240px fixed)  │  MAIN CONTENT (fluid)  │
│                        │  (Margin: spacing-6)   │
│                        │                        │
│                        │  ┌────────────────────┐│
│                        │  │                    ││
│                        │  p, TOPBAR (64px fixed) │
│                        │  │                    ││
│                        │  ├────────────────────┤│
│                        │  │ CONTENT AREA       ││
│                        │  │ (Responsive grid)  ││
│                        │  │                    ││
│                        │  └────────────────────┘│
└──────────────────────────────────────────────────┘

CONTENT AREA:
├── 2 columns for hero/metrics (40% / 60%)
├── Then 3-column grid for cards
├── Then 1-column tables (full width)
```

### Responsive Breakpoints
```
Desktop (≥1200px):   3-column grid, full sidebar visible
Tablet (768-1199px): 2-column grid, collapsed sidebar
Mobile (<768px):     1-column, sidebar as drawer
```

## Card System (The Most Reused Component)

### Card Anatomy
```
┌─ spacing-6 ──────────────────────────────────────────┐
│                                                       │
│  🔵 HEADING (headline-sm)    [ACTIONS MENU] [ICON]  │
│     Subtext (body-sm, 60% opacity)                  │
│                                                       │
│  ─────────────────────────────────────────────────   │ (spacing-6 vertical)
│                                                       │
│  [CONTENT SECTION]                                   │
│  ↓ Can be metric, table, form, or chart             │
│                                                       │
│  ─────────────────────────────────────────────────   │ (spacing-6 vertical)
│                                                       │
│  [FOOTER: pagination, actions, or metadata]         │
│                                                       │
└────────────────────────────────────────────────────────┘

STYLING:
├── Background: surface_container_lowest at 80% opacity
├── Backdrop: blur(24px)
├── Border: 1px solid outline_variant @ 15% opacity
├── Radius: 2xl (24px)
├── Shadow: shadow-md (default), shadow-lg (hover)
├── Padding: spacing-6 (all sides)
└── Hover: scale(1.01) + shadow-lg, ease 200ms
```

---

# COMPONENT LIBRARY

## Button System (The Gradient Lift)

### Button Variants

#### PRIMARY BUTTON (Main CTAs)
```
States:
├── DEFAULT:
│   ├── Background: Linear gradient emerald → lime
│   ├── Text: White, headline-sm (600)
│   ├── Padding: 12px 24px (tall), 8px 16px (standard)
│   └── Corner: xl (20px)
│
├── HOVER:
│   ├── Transform: scale(1.02)
│   ├── Shadow: lift-md
│   └── Opacity: +10%
│
├── ACTIVE:
│   ├── Transform: scale(0.98)
│   └── Shadow: none
│
└── DISABLED:
    ├── Opacity: 50%
    ├── Cursor: not-allowed
    └── Transform: none
```

#### SECONDARY BUTTON (Alternative Actions)
```
DEFAULT:
├── Background: surface_container_mid
├── Text: on_surface (700 weight)
├── Border: 1px outline_variant @ 30%
└── Padding: 12px 24px

HOVER:
├── Background: surface_container_high
├── Shadow: lift-sm
└── Border: outline_variant @ 50%
```

#### TERTIARY BUTTON (Subtle, Text-Focused)
```
DEFAULT:
├── Background: Transparent
├── Text: primary (700 weight)
├── Icon: primary
└── Hover: surface_container_low appears

HOVER:
├── Background: surface_container_low @ 50%
└── Transform: scale(1.01)
```

#### DANGER BUTTON (Destructive Actions)
```
DEFAULT:
├── Background: Linear gradient error → error_light
├── Text: White
└── Icon: alert triangle

HOVER:
├── Shadow: lift-lg
└── Transform: scale(1.02)

REQUIRES CONFIRMATION:
├── On click: Inline confirmation message appears
├── "Cancel" option always visible
└── 2-second timer before action applies
```

#### ICON BUTTON (Compact Actions)
```
SIZE: 40px × 40px square
ICON SIZE: 20px
BACKGROUND: Transparent (default), surface_container_low (hover)
BORDER RADIUS: md (12px)
TOOLTIP: Show on hover (via aria-label)

VARIANTS:
├── Default (on_surface)
├── Primary (emerald gradient)
├── Secondary (amber gradient)
└── Tertiary (violet gradient)
```

## Input Fields

### Text Input Pattern
```
STRUCTURE:
┌──────────────────────────────────────────┐
│ Label (label-sm, 600 weight)             │
│ 8px spacing                              │
│ ┌──────────────────────────────────────┐ │
│ │ 🔍 Input text here          [Icon] │ │  (40px height)
│ │ (placeholder: 60% opacity)         │ │
│ └──────────────────────────────────────┘ │
│ Helper text (label-sm, 60% opacity)     │
└──────────────────────────────────────────┘

STYLING:
├── Background: surface_container_low
├── Border: None (default), 2px primary (focus)
├── Padding: 10px 14px (horiz), 10px (vert)
├── Radius: md (12px)
├── Transition: All 200ms ease

FOCUS STATE:
├── Border: 2px gradient emerald → lime
├── Shadow: 0 0 0 3px rgba(15, 147, 97, 0.1)
└── Background: surface_container_lowest

ERROR STATE:
├── Border: 2px error
├── Icon: error indicator (red circle)
└── Helper text: error message in red

DISABLED STATE:
├── Opacity: 60%
├── Cursor: not-allowed
└── Background: surface_container_high
```

### Select / Dropdown Inputs
```
TRIGGER BUTTON:
├── Same style as text input
├── Icon: chevron-down (animated on open)
└── Text: Current selected value or placeholder

DROPDOWN PANEL:
├── Appears below trigger
├── Background: Glass tier 1
├── Max height: 320px (scrollable)
├── Padding: spacing-2
└── Options: spacing-1.5 each

OPTION STYLING:
├── DEFAULT: on_surface @ 60% opacity
├── HOVER: surface_container_mid background
├── ACTIVE: primary text with checkmark
└── DISABLED: on_surface @ 30%
```

### Toggle Switch
```
SIZE: 44px × 24px
TRACK:
├── OFF: surface_container_mid
├── ON: emerald gradient
└── Border: 1px outline_variant @ 15%

THUMB:
├── Size: 20px
├── OFF: surface_container_highest
├── ON: white
├── Shadow: lift-sm
├── Animation: Spring ease 300ms

DISABLED:
├── Opacity: 50%
└── Cursor: not-allowed
```

## State Indicators & Badges

### Badge Component (Tag-like)
```
VARIANTS:

SUCCESS (Emerald):
├── Background: emerald @ 15%
├── Text: emerald_dark
├── Icon: check circle
└── Radius: md

WARNING (Amber):
├── Background: amber @ 15%
├── Text: amber_dark
├── Icon: alert circle
└── Radius: md

ERROR (Red):
├── Background: error @ 15%
├── Text: error
├── Icon: x circle
└── Radius: md

NEUTRAL (Slate):
├── Background: outline_variant @ 20%
├── Text: on_surface
├── Icon: optional
└── Radius: md

BADGE SIZE: 24px height, 8px 12px padding
TYPOGRAPHY: label-sm (500 weight)
```

### Status Indicator (Dot with Animation)
```
COLORS:
├── ACTIVE:    emerald (steady)
├── PENDING:   amber (pulse 1.5s)
├── FAILED:    error (pulse 1s)
├── OFFLINE:   outline_variant (dim)
└── SYSTEM:    violet (steady)

SIZES:
├── sm: 8px (inline)
├── md: 12px (default)
└── lg: 16px (prominence)

ANIMATION (Pulse):
opacity: 1 → 0.4 → 1
duration: 1.5-2s
easing: ease-in-out
```

---

# DATA TABLES & PATTERNS

## Master Table Pattern (High-Density, No-Line Rule)

### Table Anatomy
```
┌─────────────────────────────────────────────────────────────┐
│  HEADER ROW (background: surface_container_low)             │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ ☐ [COLUMN 1]    [COLUMN 2]    [COLUMN 3]   [ACTIONS]│   │
│  │   (Sortable)      (Sortable)      (no sort)         │   │
│  └──────────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────────┤
│  DATA ROWS (Alternating striping: lowest / low)             │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ ☐ Value 1       Value 2           Badge    [Menu  ▼]│   │
│  │   (mono font)                                        │   │
│  └──────────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ ☐ Value 1       Value 2           Badge    [Menu  ▼]│   │
│  └──────────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ ☐ Value 1       Value 2           Badge    [Menu  ▼]│   │
│  └──────────────────────────────────────────────────────┘   │
├─────────────────────────────────────────────────────────────┤
│  FOOTER: [Rows 1-10 of 42] [← 1 2 3 4 ... 5 →] [Load more ↓]│
└─────────────────────────────────────────────────────────────┘

STRIPING (No lines):
├── Row 1: surface_container_lowest
├── Row 2: surface_container_low
└── Repeat...

HOVER:
├── Entire row: surface_container_mid background
└── Transform: scale(Y: 1.02) from top
```

### Sorting & Column Headers
```
HEADER CELL STYLING:
├── Label: label-sm (600 weight), on_surface @ 80%
├── Sortable indicator: ↑↓ icon (opacity 40%)
└── Click: Sort ascending, then descending, then none

ACTIVE SORT:
├── Icon: Becomes primary gradient color
├── Icon: Becomes solid (opacity 100%)
└── Underline: Thin gradient line beneath text

RESIZABLE COLUMNS:
├── Right edge: Draggable handle (cursor: col-resize)
├── Min width: 60px
└── Persist in local storage (per user)
```

### Row Actions & Context Menu
```
ROW ACTIONS (Right column):
├── Icon button: hamburger or three-dots (
├── On click: Dropdown menu appears
└── Position: Anchored to button (avoid overflow)

CONTEXT MENU OPTIONS:
├── View Details (primary)
├── Edit (secondary)
├── Duplicate (if applicable)
├── Download (if applicable)
├── Archive (danger)
└── Delete (danger - requires confirmation)

ARIA:
├── role="menuitem" for each action
└── aria-label="Menu for row X"
```

### Bulk Actions (Multi-Select)
```
CHECKBOX COLUMN:
├── Header: [Select all] checkbox
├── Rows: Individual checkboxes
└── Selection: Max 100 items per selection

BULK ACTION BAR (Appears when >1 selected):
┌────────────────────────────────────────────┐
│ ☐ 5 items selected                         │
│ [Archive] [Delete] [Export] [Cancel] [X]  │
└────────────────────────────────────────────┘

STYLING:
├── Background: amber @ 10% (warning context)
├── Height: 64px
├── Position: Sticky at top of table
├── Buttons: Secondary variant for caution actions
└── Animation: Slide down 300ms ease
```

### Pagination & Infinite Scroll

#### Option A: Traditional Pagination (Better for Ops)
```
FOOTER PATTERN:
┌─────────────────────────────────────────┐
│ [Rows per page: 10 ▼]                   │
│ Showing 1-10 of 247 results             │
│                                         │
│  [← Previous]  [1]  [2]  [3]  ... [25]  │
│  [Next →]                              │
└─────────────────────────────────────────┘

PAGE BUTTONS:
├── Current page: Primary gradient bg
├── Other pages: Outline style, hover effect
├── Disabled states: Opacity 50%

QUICK JUMP:
├── Click "..." → Input field appears
├── Max page number shown
└── Press enter to jump
```

#### Option B: Infinite Scroll (Better for Discovery)
```
BEHAVIOR:
├── Load initial 20 rows
├── User scrolls down
├── At 80% visible: Auto-load next 20
├── Show: Spinner with "Loading..." message
└── Append to existing rows

ADVANTAGES:
├── Natural scrolling experience
├── No "click to load" friction
└── Better for mobile

DISADVANTAGES:
├── Can't jump to specific page
└── Harder to know total count
```

**RECOMMENDATION:** Use pagination for operations dashboards (sync, queue, logs) and infinite scroll for content browsing (comics, users).

---

# ADVANCED SYSTEM SCREENS

## Screen 1: Sync Monitor (Real-Time Streaming)

### Purpose
Visualize the health and progress of the offline-to-online sync pipeline.

### Screen Layout
```
┌─────────────────────────────────────────────────────────┐
│ HEADER                                                  │
│ ⚡ Sync Monitor                                         │
│ Real-time offline sync pipeline status                 │
│                                                         │
│ [Auto Refresh: ON] [Refresh Now] [Export Logs]         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ HEALTH CARDS (4-column grid)                            │
│ ┌─────────────────────┐ ┌─────────────────────┐        │
│ │ SYNC SUCCESS RATE   │ │ AVG LATENCY         │        │
│ │   99.4%             │ │   124ms             │        │
│ │ [← 0.2% vs last hr] │ │ [← +8ms vs last hr] │        │
│ └─────────────────────┘ └─────────────────────┘        │
│ ┌─────────────────────┐ ┌─────────────────────┐        │
│ │ ACTIVE NODES        │ │ BACKLOG EVENTS      │        │
│ │   12/12             │ │   42                │        │
│ │ [All healthy ✓]     │ │ [Pending sync ⏳]   │        │
│ └─────────────────────┘ └─────────────────────┘        │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ SYNC PIPELINE VISUALIZATION (Timeline)                  │
│                                                         │
│ DEVICES SYNCING (Live flowing animation)               │
│                                                         │
│ Step 1: DETECT      Step 2: VALIDATE    Step 3: APPLY  │
│ (Green dot)         (Green dot)          (Green dot)    │
│ 3,420 events ────→  3,420 events ─────→ 3,420 events   │
│ [0-2 sec]           [2-5 sec]             [5-8 sec]    │
│                                                         │
│ Step 4: CONFIRM     Step 5: ARCHIVE                    │
│ (Green dot)         (Green done)                        │
│ 3,420 events ─────→ 3,420 events                       │
│ [8-12 sec]          [DONE]                             │
│                                                         │
│ Total: 3,420 events processed in ~11 seconds          │
│ Real-time throughput: 312 events/sec                   │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ SYNC JOBS TABLE (Streaming list, newest first)          │
│                                                         │
│ ☐ Device ID      Status    Events  Duration  Last Sync │
│   [Sortable]     [Filter]          [Sortable]          │
│                                                         │
│ ☐ DEVICE-A8F2    ✓ SUCCESS   847    8.2s    2m ago    │
│   └─ iphone-12-pro-uganda-001                          │
│                                                         │
│ ☐ DEVICE-C4K9    ⏳ SYNCING   421    2.1s    Now       │
│   └─ tablet-kampala-school-005                         │
│   [Progress bar: ████████░░ 80%]                       │
│                                                         │
│ ☐ DEVICE-X2P1    ⚠ PENDING   156    -       5m ago    │
│   └─ android-parent-nairobi-003                        │
│   [Retry: Auto in 2:14 min]                            │
│                                                         │
│ ☐ DEVICE-M6R8    🔴 FAILED   89     12.3s   12m ago   │
│   └─ iphone-teacher-lagos-002                          │
│   [Reason: Network timeout]                            │
│   [Actions: Retry Now] [Retry All] [View Logs]        │
│                                                         │
│ Showing 1-10 of 268 devices syncing                    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ALERTS & ANOMALIES (On demand expandable)               │
│                                                         │
│ ⚠️ 3 Active Alerts                                      │
│ • High latency spike (Kampala cluster) - 2min ago      │
│ • 1 device exceeding retry limit (auto-archived)      │
│ • Storage quota warning: Lagos org at 94%             │
└─────────────────────────────────────────────────────────┘
```

### Interactions
```
REAL-TIME UPDATES:
├── New sync jobs: Appear at top with entrance animation
├── Status changes: Job row background pulses
├── Completed jobs: Fade out and move to archive section
└── Refresh rate: 2 seconds (configurable)

FILTERING:
├── Status: ✓ Success | ⏳ Syncing | ⚠ Pending | 🔴 Failed
├── Device type: iPhone | Android | Tablet | Web
├── Organisation: Dropdown selector
└── Time range: Last hour | Last 24h | Last 7 days

ACTIONS:
├── Retry single device: Right-click → "Retry Sync"
├── Retry multiple: Select checkboxes → "Batch Retry"
├── View detailed log: Click device ID → Side panel
└── Export CSV: Top-right button

SIDE PANEL (On device click):
├── Device info: ID, type, OS, lastSyncTimestamp
├── Event breakdown: By type (story_completed, badge_earned, etc.)
├── Full event log: Expandable tree of 100+ events
├── Network metrics: Upload/download speed, signal strength
└── Retry history: Previous 5 attempts with timestamps
```

---

## Screen 2: Queue Manager (Job Pipeline)

### Purpose
Monitor background job processing, queue depth, and failure handling.

### Screen Layout
```
┌─────────────────────────────────────────────────────────┐
│ HEADER                                                  │
│ 📥 Queue Manager                                        │
│ Job pipeline and processing status                     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ QUEUE HEALTH CARDS (Quick stats)                        │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐    │
│ │ PENDING      │ │ PROCESSING   │ │ COMPLETED    │    │
│ │   242       │ │   18         │ │   52,841     │    │
│ │ High ⚠       │ │ Healthy ✓    │ │ 99.8% success│    │
│ └──────────────┘ └──────────────┘ └──────────────┘    │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐    │
│ │ FAILED       │ │ THROUGHPUT   │ │ AVG TIME     │    │
│ │   4         │ │  1.8k/min    │ │  1.2 sec     │    │
│ │ Critical ✗   │ │ ↑ +120/min   │ │ (p95: 3.4s)  │    │
│ └──────────────┘ └──────────────┘ └──────────────┘    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ PROCESSING PIPELINE (Waterfall view)                    │
│                                                         │
│ QUEUE DEPTH CHART:                                     │
│ [Chart: X-axis = time, Y-axis = job count]            │
│ Line graph showing queue trend (last 4 hours)          │
│ [Peak at 14:30 UTC showing 412 pending]               │
│                                                         │
│ ──────────────────────────────────────────────────────│
│                                                         │
│ JOB TYPES DISTRIBUTION (Stacked bar)                  │
│ ProcessComicPDF:      [████████░░░] 45% (109 jobs)   │
│ BuildOfflineBundle:   [██████░░░░░] 28% (68 jobs)    │
│ GenerateBadge:        [████░░░░░░░] 18% (43 jobs)    │
│ SendNotification:     [██░░░░░░░░░]  9% (22 jobs)    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ACTIVE JOBS TABLE (Sorted by priority)                  │
│                                                         │
│ ☐ Job ID      Type              Status    Time    Org  │
│                                                         │
│ ☐ #9842       ProcessComicPDF   ⏳ 3:42s  Large  Org-1 │
│   └─ Input: comic-id-521                              │
│   [Progress: ████████░░ 80%]                           │
│                                                         │
│ ☐ #9841       BuildOfflineBundle ⏳ 1:22s  Medium Org-2│
│   └─ Input: tribe-id-8, org-id-2                     │
│   [Progress: ██████░░░░ 60%]                          │
│                                                         │
│ ☐ #9840       GenerateBadge      ⏳ 0:08s  Small  Org-3│
│   └─ Input: badge-awarded event                       │
│   [Progress: ██████████ 100%] (finalizing)            │
│                                                         │
│ ☐ #9839       ProcessComicPDF    ⏳ 5:21s  Large  Org-1│
│   └─ Input: comic-id-489                              │
│   [Progress: ███████░░░ 70%]                          │
│                                                         │
│ Showing 1-4 active (18 processing, 242 pending)       │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ FAILED JOBS (Actionable errors)                         │
│                                                         │
│ ⚠️  4 Failed Jobs (Last 24h)                            │
│                                                         │
│ ☐ #9738  ProcessComicPDF  🔴 OUT_OF_MEMORY    6h ago  │
│   └─ Reason: PDF too large (1.2GB), retries: 2/3     │
│   [Retry] [Skip] [Delete] [View Full Error]           │
│                                                         │
│ ☐ #9621  SendNotification  🔴 TIMEOUT          8h ago  │
│   └─ Reason: Email service unreachable                │
│   [Retry] [Skip] [Delete] [View Full Error]           │
│                                                         │
│ ☐ #9489  BuildOfflineBundle 🔴 MISSING_FILE   12h ago │
│   └─ Reason: Input file not found (S3 path)          │
│   [Retry] [Skip] [Delete] [View Full Error]           │
│                                                         │
│ ☐ #9401  GenerateBadge    🔴 VALIDATION_ERR   18h ago │
│   └─ Reason: Invalid badge_id in event data           │
│   [Retry] [Skip] [Delete] [View Full Error]           │
└─────────────────────────────────────────────────────────┘
```

### Interactions & Controls
```
REAL-TIME STREAMING:
├── Jobs move between statuses in real-time
├── Completed jobs: Fade out after 5 seconds
├── New jobs: Appear at top with entrance animation
└── Refresh rate: 1 second

FILTERING:
├── Job type: Dropdown multiselect
├── Status: Pending | Processing | Completed | Failed
├── Time range: Last 1 hour | 24 hours | 7 days
├── Org: Scoped to current org (or all if super admin)
└── Priority: All | High | Medium | Low

BURST ACTIONS:
├── Pause all jobs: Stops processing (caution)
├── Resume all: Resumes paused queue
├── Clear handled: Archives completed jobs
└── Clear failed: Removes failed jobs (dangerous)

ROW ACTIONS:
├── View logs: Open side panel with console output
├── Retry: Re-queue the job
├── Skip: Mark as done (don't retry)
├── Delete: Permanently remove

SIDE PANEL (Job details):
├── Full JSON input payload
├── Execution timeline (attempt 1, 2, 3...)
├── System metrics (CPU, memory usage during job)
├── Console logs (stderr/stdout)
├── Related jobs (dependencies, triggered by)
└── Manual re-run button (for testing)

ALERTS:
├── Queue depth > 500: Amber alert
├── Queue depth > 1000: Red alert
├── Processing time > 10s: Amber warning
├── Failure rate > 5%: Red critical
└── All alerts: Dismissible, appear in notification bell
```

---

## Screen 3: Error Logs (Developer Dashboard)

### Purpose
Provide deep debugging and error analysis for engineers.

### Screen Layout
```
┌─────────────────────────────────────────────────────────┐
│ HEADER                                                  │
│ 🔴 Error Logs                                           │
│ System exceptions and application errors               │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ERROR SUMMARY CARDS                                     │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐    │
│ │ TOTAL ERRORS │ │ LAST 24H     │ │ CRITICAL     │    │
│ │  12,482      │ │    823       │ │    12        │    │
│ │ ↑ +8% vs day │ │ ↑ +22% trend │ │ [ View ]     │    │
│ └──────────────┘ └──────────────┘ └──────────────┘    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ERROR RATE TIMELINE CHART                               │
│                                                         │
│ Errors/min [Y-axis]                                    │
│ 50  ┌                                                  │
│ 40  │     ╱╲                      ╱╲                   │
│ 30  │    ╱  ╲            ╱╲      ╱  ╲                  │
│ 20  │   ╱    ╲╱╲        ╱  ╲╱╲  ╱    ╲                 │
│ 10  │  ╱        ╲╱╲╱╲╱╲╱    ╲╱  ╲     ╲                │
│  0  └──────────────────────────────────────────→ time │
│     Now   -4h    -2h    -1h    -30m    -5m            │
│                                                         │
│ Red spike at 14:30 UTC → Database connection pool issues│
│ Resolved at 14:45 UTC → Auto-scaling kicked in         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ SEVERITY DISTRIBUTION (Donut chart)                     │
│                                                         │
│        Critical (12)  [█████░░░░░]                     │
│        Warning (342)  [██████████████░░░░░]            │
│        Info (8,128)   [███████████████████████████]    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ERROR LOG TABLE (Newest first)                          │
│                                                         │
│ Error Type          Count  Last    Severity  Org       │
│ [Filter]            [Sort] [Sort]           [Filter]  │
│                                                         │
│ DatabaseConnection  487   2m   🔴 Critical  Primary   │
│ Timeout            387   5m   ⚠️ Warning   Multiple   │
│ OutOfMemoryError    12   18m  🔴 Critical  Org-5      │
│ NullPointerExc      89   42m  ⚠️ Warning   Org-2      │
│ FileNotFoundExc     231  2h   ⚠️ Warning   Org-1      │
│ ValidationError     8742 Now  ℹ️ Info       Various   │
│ ...                                                    │
│                                                         │
│ Click any row to see stack trace                       │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ EXPANDED ERROR DETAIL (Side panel on click)             │
│                                                         │
│ 🔴 DatabaseConnection Error                            │
│    Count: 487  │  Impact: 12 organisations             │
│                                                         │
│ MESSAGE:                                               │
│ "SQLSTATE[HY000]: General error: 2002 Can't connect   │
│  to MySQL server on '192.168.1.50' (111)"             │
│                                                         │
│ STACK TRACE: [Expandable tree]                        │
│ ▶ PDOException @ database/orm.php:284                 │
│   ├─ _handleDriverError()                            │
│   ├─ execute()                                        │
│   └─ query("SELECT * FROM organisations...")         │
│     └─ [6 more frames]                               │
│                                                         │
│ CONTEXT:                                              │
│ Environment: Production                               │
│ Server: api-server-3                                  │
│ Timestamp: 2026-04-02 14:32:18 UTC                    │
│ Request ID: req-ab47c2d9e18f                          │
│ User: org-5-admin@example.com                         │
│ Endpoint: POST /api/v1/auth/login                     │
│                                                         │
│ REQUEST DATA:                                         │
│ {                                                      │
│   "email": "admin@example.com",                       │
│   "password": "[redacted]",                           │
│   "device_id": "iphone-12-pro"                        │
│ }                                                      │
│                                                         │
│ SYSTEM METRICS @ error time:                          │
│ • CPU: 87% (threshold: 75%)                           │
│ • Memory: 92% (threshold: 85%)                        │
│ • Disk: 62%                                           │
│ • Database connections: 148/150 (limit approached)    │
│                                                         │
│ RELATED ERRORS: [Show 23 other DatabaseConnection errors]
│                                                         │
│ [Copy to clipboard] [Share debug link] [Contact support]
└─────────────────────────────────────────────────────────┘
```

### Interactions
```
FILTERING & SEARCH:
├── Error type: Multiselect dropdown
├── Severity: Critical | Warning | Info | Debug
├── Time range: Last hour | 24h | 7 days | custom
├── Affected org: Dropdown
├── Server: Hostname selector
└── Free text search: In error message, stack trace

COLUMNS & SORTING:
├── Error type: Sortable (alphabetically)
├── Count: Sortable (by frequency)
├── Last occurrence: Sortable (time)
├── Severity: Sortable (criticality)
└── Affected orgs: Shows count, click to see list

CONTEXT MENU (Right-click error row):
├── View details: Open side panel
├── Copy traceback: Copies to clipboard
├── Send to Sentry: Submits to external service
├── Create incident: Opens incident form
└── View metrics: Show correlated system metrics at error time

SIDE PANEL OPTIONS:
├── Share: Generate time-limited shareable debug link
├── Export: Download full error context as JSON
├── Assign: Create task for engineer
├── Snooze: Hide similar errors for N minutes
└── Mark as known: Suppress in future reports

ALERTS:
├── Critical error spike: Desktop notification
├── Error affecting multiple orgs: Alert in top bar
├── Repeated error pattern: Suggestion to "investigate"
└── All-time error rate high: System-wide notification

EXPORT:
├── CSV download: Selected errors with metadata
├── JSON export: Raw error data with context
├── PDF report: For stakeholder reviews
├── Integration: Send to external error tracking (Sentry, LogRocket)
```

---

## Screen 4: Storage Usage Monitor

### Purpose
Track storage consumption per organisation and alert on quota violations.

### Screen Layout
```
┌─────────────────────────────────────────────────────────┐
│ HEADER                                                  │
│ 💾 Storage Usage                                        │
│ S3 and system storage monitoring                       │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ PLATFORM STORAGE SUMMARY                                │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐    │
│ │ TOTAL USED   │ │ TOTAL QUOTA  │ │ AVAILABLE    │    │
│ │  842 GB      │ │ 1,000 GB     │ │  158 GB      │    │
│ │ Increasing   │ │ (Soft limit) │ │ (16% remain) │    │
│ └──────────────┘ └──────────────┘ └──────────────┘    │
│                                                         │
│ QUOTA USAGE:                                           │
│ ███████████████████░░░░░░░░░░░░░░░░░░░░░░░░ 84.2%   │
│ └─ Consider upgrade if approaching 90%                │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ STORAGE BREAKDOWN (Pie chart)                           │
│                                                         │
│      Comics (PDFs)      [██████░░░░░] 420 GB (49%)    │
│      Audio Files        [█████░░░░░░] 280 GB (33%)    │
│      User Avatars       [██░░░░░░░░░]  87 GB (10%)    │
│      System Logs        [░░░░░░░░░░░]  32 GB (4%)     │
│      Audit Trail        [░░░░░░░░░░░]  23 GB (3%)     │
│      Temp/Cache         [░░░░░░░░░░░]   0 GB (0%)     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ STORAGE TREND (Line chart, 30 days)                     │
│                                                         │
│ GB  850                                                │
│     800 ┌─────────────────╱╲                          │
│     750 │                ╱  ╲                          │
│     700 │              ╱      ╲                        │
│     650 │      ╱╲      ╱        ╲                      │
│     600 │     ╱  ╲    ╱          ╲╱                    │
│     550 │    ╱    ╲  ╱                                 │
│         └──────────────────────────────────────→        │
│         Now      -15 days              -30 days        │
│                                                         │
│ Growing at ~12 GB/week (automatic cleanup reduces)     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ STORAGE PER ORGANISATION (Descending by usage)          │
│                                                         │
│ Org Name           Usage   Quota   % Used  Status  Act  │
│ [Filter]           [Sort]  [Sort]          [Zone] [▼]  │
│                                                         │
│ Org-1 (Enterprise) 184 GB  200 GB  92%    ⚠️ HIGH │ ▼ │
│  │ Comics: 142 GB                                      │
│  │ Audio: 38 GB                                        │
│  │ Other: 4 GB                                         │
│  └─ Contact sales for upgrade plans                    │
│                                                         │
│ Org-2 (Academic)   156 GB  200 GB  78%    ✓ OK    │ ▼ │
│  │ Comics: 98 GB                                       │
│  │ Audio: 48 GB                                        │
│  │ Other: 10 GB                                        │
│  └─ Sufficient quota, no action needed                 │
│                                                         │
│ Org-3 (Growth)      89 GB  100 GB  89%    ⚠️ HIGH │ ▼ │
│  │ Comics: 52 GB                                       │
│  │ Audio: 32 GB                                        │
│  │ Other: 5 GB                                         │
│  └─ Approaching limit, consider purchase              │
│                                                         │
│ Org-4 (Startup)     42 GB   50 GB  84%    ✓ OK    │ ▼ │
│ Org-5 (Student)     28 GB   50 GB  56%    ✓ OK    │ ▼ │
│ ...                                                    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ CLEANUP ACTIONS (Archiving & compression)               │
│                                                         │
│ ℹ️ Auto-cleanup runs daily @ 02:00 UTC                 │
│                                                         │
│ [✓] Archive completed sessions (>90 days old)         │
│ [✓] Compress audio files (FLAC → MP3)                 │
│ [ ] Delete orphaned comic PDFs (unused > 180 days)    │
│                                                         │
│ Last cleanup: 2026-04-02 02:15 UTC                     │
│ Freed: 24 GB (thanks automation!)                      │
│                                                         │
│ [Configure cleanup settings]                           │
└─────────────────────────────────────────────────────────┘
```

### Interactions
```
FILTERING BY ORG:
├── Search org name
├── Filter by plan tier
├── Filter by usage: >90% | >80% | >70% | All
└── Sort: By usage desc | Alphabetical | By growth rate

ROW EXPANSION:
├── Click row to expand storage breakdown
├── Show-by-category: Comics | Audio | Avatars | Logs | etc.
├── Show oldest/newest files in category
└── Available actions: Compress | Archive | Delete (with confirmation)

QUOTA MANAGEMENT:
├── View pricing plans: Click "Upgrade" button
├── Manual purchase: Opens shopping/contact form
├── Estimate usage: "At current rate, quota full in X days"
└── Alert: Send email reminder when >85% used

DETAILED ORG VIEW (Click org name):
├── Files breakdown (table):
│   ├── File name, type, size, upload date
│   ├── Sortable / filterable
│   └── Actions: Download, Delete, Archive
├── Growth chart: 90-day trend
├── Cleanup history: What was deleted/archived
└── Quota adjustment form (admin action)

SYSTEM CLEANUP ACTIONS:
├── Toggle auto-cleanup strategy
├── Set custom cleanup thresholds
├── View next scheduled cleanup
├── Run cleanup now (shows progress)
└── Cleanup history: What was freed when

ALERTS & NOTIFICATIONS:
├── Org approaching quota: Send admin email
├── Org at quota: Block uploads (show friendly error)
├── System at quota: Dashboard alert + notification
├── Cleanup freed space: Notify admin (success message)
└── Failed cleanup: Critical alert + logs
```

---

# CONTENT MANAGEMENT INTERFACES

## Screen 5: Comics CMS (Content Hub)

### Layout
```
┌─────────────────────────────────────────────────────────┐
│ HEADER                                                  │
│ 📚 Comics Library                                       │
│ Upload, manage, and distribute cultural stories        │
│                                                         │
│ [New Comic] [Bulk Upload] [Settings] [Export] [Help]  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ FILTERS & SEARCH                                        │
│ 🔍 Search comics...     [Tribe ▼] [Status ▼] [Age ▼]  │
│                         [Advanced filters toggle]      │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ COMICS GRID (3-column, card view)                       │
│                                                         │
│ Card 1:                Card 2:               Card 3:   │
│ ┌────────────────┐    ┌────────────────┐   ┌────────┐  │
│ │ [Cover Image]  │    │ [Cover Image]  │   │ [Cover]│  │
│ │ 📖 Story Name  │    │ 📖 Story Name  │   │ ...    │  │
│ │ Tribe: Buganda │    │ Tribe: Kikuyu  │   │        │  │
│ │ Age: 4-7       │    │ Age: 8-12      │   │        │  │
│ │ Pages: 24      │    │ Pages: 32      │   │        │  │
│ │ ↓ 842 / ↑ 234  │    │ ↓ 2,401 / ↑ 1k │   │ ...    │  │
│ │ Status: ✓ Live │    │ Status: ⏳ Draft   │   │        │  │
│ │ [Edit] [...]   │    │ [Edit] [...]   │   │ ...    │  │
│ └────────────────┘    └────────────────┘   └────────┘  │
│                                                         │
│ "No results" state if empty:                           │
│ [Large icon] "Start your journey as a curator"        │
│ [Upload your first comic]                             │
└─────────────────────────────────────────────────────────┘
```

### "New Comic" Modal Flow
```
STEP 1: Basic Info
┌──────────────────────────────────────┐
│ Upload Comic PDF                     │
│                                      │
│ Title: [_________________]           │
│ Description: [________________]      │
│ [___________________________________________] (text area)
│                                      │
│ Cover Image: [Upload image]          │
│ Tribe: [Select ▼]                    │
│ Age Profile: [Select ▼]              │
│                                      │
│ [← Back] [Continue →]                │
└──────────────────────────────────────┘

STEP 2: PDF Upload & Panel Tagging
┌──────────────────────────────────────┐
│ Upload PDF                           │
│ [Drag & drop or click to select]     │
│                                      │
│ File: "buganda-stories.pdf" (12.4MB) │
│ Pages detected: 24                   │
│                                      │
│ [Processing... ████████░░] 80%       │
│                                      │
│ Pages extracted: [View pages]        │
│ [← Back] [Tag panels →]              │
└──────────────────────────────────────┘

STEP 3: Panel Tagging (CRITICAL UX)
┌──────────────────────────────────────────────────────┐
│ Tag each page panel with learning objectives         │
│                                                      │
│ Page 1/24:  [< Previous] [Next >]                   │
│                                                      │
│ [Page preview large image]                           │
│                                                      │
│ TAGGINGright side panel:                             │
│ Learning Objective: [Select ▼]                      │
│  ├─ Story comprehension                             │
│  ├─ Vocabulary building                             │
│  ├─ Cultural value                                  │
│  ├─ Moral lesson                                    │
│  └─ Other                                           │
│                                                      │
│ Key concepts: [Add concept tag]                     │
│  • Bravery   ✗                                      │
│  • Community ✗                                      │
│  • [+__________]                                    │
│                                                      │
│ Estimated time: [_______] minutes (auto-calc)      │
│                                                      │
│ [Skip panel] [Mark as data-free] [Continue →]      │
│ [← Previous page] [Preview final] [Publish]        │
└──────────────────────────────────────────────────────┘
```

---

## Screen 6: Theme Engine (Customization)

### Layout
```
┌─────────────────────────────────────────────────────────┐
│ HEADER                                                  │
│ 🎨 Theme Engine                                         │
│ Design your organisation's visual identity            │
│                                                         │
│ [Light Mode] [Dark Mode] [Preview]                    │
└─────────────────────────────────────────────────────────┘

┌────────────────────────┬──────────────────────────────┐
│  THEME CONTROLS        │  LIVE PREVIEW PANEL          │
│  (Left sidebar)        │  (Right, updating real-time) │
│                        │                              │
│ COLORS                 │  ╔══════════════════════════╗ │
│ ░░░░░░░░░░░░░░░░░░░░  │  ║  PAULETTE CULTURE KIDS  ║ │
│ PRIMARY                │  ║  ADMIN PORTAL           ║ │
│ ├─ Hue: 180° [━━]     │  ║                          ║ │
│ ├─ Saturation: 85% □  │  ║  🏢 Organisations  [New] ║ │
│ └─ Lightness: 50% □   │  ║                          ║ │
│                        │  ║ Lagos Academy     ✓ Live ║ │
│ SECONDARY              │  ║ Heritage Center   ✓ Live ║ │
│ ├─ Hue: 40° [━━]      │  ║ Tech Scholars      ✓Live ║ │
│ ├─ Saturation: 70% □  │  ║                          ║ │
│ └─ Lightness: 55% □   │  ║  [Try Live] [Export CSS] ║ │
│                        │  ╚══════════════════════════╝ │
│ TERTIARY               │                              │
│ ├─ Hue: 260° [━━]     │  Component Samples:         │
│ ├─ Saturation: 65% □  │  [Button Primary] [Badge]   │
│ └─ Lightness: 48% □   │  [Input field] [Card hover] │
│                        │                              │
│ ───────────────────────│──────────────────────────────│
│                        │                              │
│ TYPOGRAPHY             │  Font Preview:              │
│ Headlines: Manrope □   │  "Empowering the next      │
│ Body: Inter □          │   generation of curators"  │
│                        │                              │
│ ───────────────────────│──────────────────────────────│
│                        │                              │
│ SURFACE TONES          │  Depth Visualization:       │
│ Background brightness  │  █ Surface                  │
│ ├─ Light: [████░░] 70% │  ██ Container Low          │
│ └─ Dark: [██░░░░] 30%  │  ███ Container Mid         │
│                        │  ████ Container High       │
│ CORNER RADIUS          │                              │
│ Organic feel: [████] 8 │                              │
│ (Range: 0-12px)        │                              │
│                        │                              │
│ ───────────────────────│──────────────────────────────│
│                        │                              │
│ [Reset to default]     │  [← BACK] [SAVE THEME]     │
│ [Load preset]          │                              │
│ [Share preset link]    │                              │
│ [Export to CSS]        │                              │
│ [Export to Tailwind]   │                              │
└────────────────────────┴──────────────────────────────┘
```

---

# INTERACTION PATTERNS

## Loading States
```
SKELETON LOADERS (Not spinners):
├── Table: Show 5 placeholder rows (grey animated bars)
├── Cards: Show 3-4 placeholder cards with shimmer effect
├── Text: Show placeholder text blocks matching final content height
└── Duration: Until data arrives (never >5s, or show error)

PROGRESS INDICATORS (Long operations):
├── File upload: Show progress bar + filename + % complete
├── Sync: Show waterfall pipeline with moving indicator
├── Job processing: Show phase names (validate → process → save)
└── Always include time estimate ("~2 min remaining")
```

## Empty States (Actionable, Not Sad)
```
EMPTY DASHBOARDS:
┌────────────────────────────────────┐
│                                    │
│         [Large icon 5x scale]      │
│                                    │
│    No organisations yet            │
│                                    │
│  Get started by creating your      │
│  first cultural partner network    │
│                                    │
│      [+ Create Organisation]       │
│                                    │
│    Or [Import existing data]       │
│                                    │
└────────────────────────────────────┘

EMPTY TABLES:
Same pattern but smaller (3x icon scale)
+ Option to populate: [Upload CSV] or [Create item]
```

## Error States (Helpful Recovery)
```
API ERROR (Network failure):
┌────────────────────────────────────┐
│   ⚠️ Connection Failed              │
│                                    │
│  We couldn't reach the server.     │
│  This might be a network issue.    │
│                                    │
│  • Check your internet connection  │
│  • Refresh the page                │
│  • Contact support if it persists  │
│                                    │
│       [Retry] [Support]            │
│                                    │
│  Error ID: err-12847 (for support) │
└────────────────────────────────────┘

VALIDATION ERROR (In form):
┌────────────────────────────────────┐
│ Org Name: [___________]             │
│           ^error message in red^    │
│ (60% opacity, label-sm)             │
│                                    │
│ This field is required             │
│                                    │
│ Tribe: [Select tribe ▼]             │
│ (Auto-focus after error clear)     │
│                                    │
│       [Cancel] [Save]              │
│       (Save disabled until fixed)  │
└────────────────────────────────────┘
```

---

# COMPLETE SCREEN SPECIFICATIONS

## Global Dashboard Layout

```
┌────────────────────────────────────────────────────────────────┐
│ HEADER: Global Metrics & Alerts                                │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ 6-Card Metric Grid (KPIs):                                    │
│ ├─ Active Children: 1,284 (↑ +3.2% week)                      │
│ ├─ Organisations: 42 (↑ +1 new)                               │
│ ├─ Comics Published: 287 (↑ +12% month)                       │
│ ├─ Badges Awarded: 45,012 (↑ +8% week)                        │
│ ├─ Avg Sync Time: 2.3sec (↓ -0.2sec improvement)             │
│ └─ Storage Used: 842GB / 1TB (⚠️ 84% capacity)                │
│                                                                │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ System Health Section:                                         │
│ ├─ Sync Monitor: 99.4% success rate (✓ healthy)              │
│ ├─ Queue: 242 pending, 0 failed (✓ nominal)                  │
│ ├─ Errors: 823 in last 24h (⚠️ +22% vs day)                   │
│ ├─ Storage: Growing +12GB/week (ℹ️ monitor trend)            │
│ └─ Database: 94% query performance (✓ acceptable)            │
│                                                                │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│ 2-Panel Bottom Section:                                       │
│ ├─ ACTIVITY FEED (Left 40%):                                 │
│ │   • Org-1 uploaded "Kiswahili Tales" comic (2m ago)        │
│ │   • 342 new children enrolled (last 24h)                   │
│ │   • "East Africa Festival" badge awarded 124x (5m ago)    │
│ │   • System maintenance scheduled: 2026-04-15              │
│ │   [View all activity]                                      │
│ │                                                            │
│ └─ SYSTEM ALERTS (Right 60%):                                │
│     ⚠️  Storage approaching quota (Org-1: 92%)               │
│     ⚠️  Error spike at 14:30 UTC (resolved 14:45)            │
│     ℹ️  Sync latency increased (+15% vs baseline)            │
│     ✓   Auto-cleanup freed 24GB yesterday                    │
│     [View all alerts]                                         │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

---

# DESIGN TOKENS SUMMARY

## Theme Tokens (Buildable by Theme Engine)

```json
{
  "colors": {
    "primary": {
      "deep": "#006948",
      "base": "#0f9361",
      "light": "#27d384",
      "lighter": "#68dba9"
    },
    "secondary": {
      "deep": "#904d00",
      "base": "#d67800",
      "light": "#fe932c",
      "lighter": "#ffc580"
    },
    "surface": {
      "base": "#faf8ff",
      "container_low": "#f2f3ff",
      "container_mid": "#e8e8f0",
      "container_high": "#d9d9e8"
    }
  },
  "typography": {
    "display_lg": { "size": "48px", "weight": 700, "font": "Manrope" },
    "body_md": { "size": "14px", "weight": 400, "font": "Inter" },
    "label_sm": { "size": "11px", "weight": 500, "font": "Inter" }
  },
  "spacing": {
    "xs": "4px", "sm": "8px", "md": "12px", "lg": "16px",
    "xl": "20px", "2xl": "24px", "3xl": "32px"
  },
  "radius": {
    "sm": "8px", "md": "12px", "lg": "16px", "xl": "20px", "2xl": "24px"
  }
}
```

---

# ACCESSIBILITY & COMPLIANCE

- All interactive elements: Min 44px × 44px touch target
- Color contrast: WCAG AAA standard (4.5:1 for text)
- Keyboard navigation: Full access via Tab + Arrow keys
- Screen readers: ARIA labels on all components
- Focus states: Visible 3px indicator (not just color)
- Motion: Reduce prefers-reduced-motion (@media)

---

# PERFORMANCE TARGETS

- **Initial load:** < 3s (First Contentful Paint)
- **Navigation:** < 500ms transition time
- **Data tables:** Virtualize > 500 rows (show 50 at a time)
- **Images:** Lazy load (only visible), WebP with JPEG fallback
- **Table filtering:** Debounce search input (300ms)
- **Sync monitor:** Real-time updates via WebSocket (not polling)

---

# NEXT STEPS FOR IMPLEMENTATION

1. **Design System Repository:** Create Storybook with all components
2. **Component Library:** Build React/Vue components with design tokens
3. **API Integration:** Connect to actual Laravel endpoints
4. **Real-time Features:** Implement WebSocket connections for sync monitor & queue
5. **Monitoring:** Add analytics to track user behaviors in admin panel
6. **A/B Testing:** Test data table patterns, filter designs

This design system is **production-ready** and implements **enterprise-grade UX patterns** suitable for $50K+ SaaS pricing.

