```markdown
# Design System Strategy: Editorial SaaS & Cultural Elegance

## 1. Overview & Creative North Star
**Creative North Star: "The Modern Curator"**

This design system is a departure from the sterile, rigid layouts typical of enterprise software. It is an "Editorial SaaS" experience that balances the high-stakes precision of a Super Admin dashboard with the warmth and vibrancy of cultural heritage. 

We break the "standard template" look through **intentional asymmetry and tonal depth**. By utilizing extreme corner radii (24px), glassmorphism, and high-contrast typography scales, we transform a data-heavy interface into a curated digital space. The goal is to make the user feel like an orchestrator of a premium brand, rather than a data entry clerk.

---

## 2. Colors & Surface Architecture

### Palette Philosophy
The palette uses **Primary Emerald** to signify growth and stability, **Secondary Amber** to evoke the sun and energy of African landscapes, and **System Violet** to provide a sophisticated, tech-forward "Enterprise" grounding.

| Role | Token | Value |
| :--- | :--- | :--- |
| **Primary** | `primary` | #006a44 |
| **Secondary** | `secondary` | #8e4e00 |
| **Tertiary** | `tertiary` | #752fd6 |
| **Base Surface** | `surface` | #faf8ff |
| **Low Container** | `surface_container_low` | #f4f3fa |

### The "No-Line" Rule
**Explicit Instruction:** Do not use 1px solid borders to section content. Traditional borders create visual noise and "box in" the data. Boundaries must be defined solely through:
1.  **Background Color Shifts:** Placing a `surface_container_lowest` card on a `surface_container_low` background.
2.  **Subtle Tonal Transitions:** Using the hierarchy of surface tiers to suggest separation.

### Surface Hierarchy & Nesting
Treat the UI as a physical stack of premium materials. 
*   **Level 0 (The Floor):** `surface` (#faf8ff) - The canvas.
*   **Level 1 (Sections):** `surface_container_low` (#f4f3fa) - Large layout groupings.
*   **Level 2 (Cards):** `surface_container_lowest` (#ffffff) - Interactive elements and data containers.

### The Glass & Gradient Rule
To achieve "Editorial Elegance," use **Glassmorphism** for floating elements (Navigation bars, Modals, Popovers). 
*   **Background:** `rgba(255, 255, 255, 0.6)`
*   **Backdrop Blur:** 24px–40px.
*   **CTA Gradients:** Use a linear gradient from `primary` (#0f9361) to `primary_container` (#008557) at 135 degrees to add "soul" and depth to key actions.

---

## 3. Typography: Geometric Authority
We pair the geometric precision of **Manrope** for headlines with the utilitarian clarity of **Inter** for data.

*   **Display & Headlines (Manrope):** These are your "Editorial Voice." Use `display-lg` (3.5rem) with tight letter-spacing for high-impact landing areas. Headlines should feel bold and authoritative.
*   **Body & Labels (Inter):** Optimized for legibility at small sizes. Even in dense admin tables, the generous x-height of Inter ensures clarity.
*   **Hierarchy Tip:** Use `tertiary` (Violet) for specialized labels to provide a distinct "System Meta" layer that sits apart from the primary content.

---

## 4. Elevation & Depth

### The Layering Principle
Depth is achieved via **Tonal Layering**. Instead of using shadows on every element, stack the container tiers. A white card (`surface_container_lowest`) sitting on a cool-grey section (`surface_container_low`) creates a natural, soft lift that is easier on the eyes than a drop shadow.

### Ambient Shadows
When an element must "float" (e.g., a primary Modal or a Toast notification):
*   **Blur:** 40px–64px.
*   **Color:** `rgba(26, 27, 32, 0.06)` (A tinted version of `on_surface`).
*   **Avoid:** Harsh, black, or high-opacity shadows.

### The "Ghost Border" Fallback
If contrast is required for accessibility, use a **Ghost Border**:
*   **Stroke:** 1px.
*   **Token:** `outline_variant` (#bdcabf).
*   **Opacity:** 20%. 
*   *Never use 100% opaque borders for decorative containment.*

---

## 5. Components

### Buttons & Interaction
*   **Primary Action:** 20px (xl) corner radius. Solid `primary` gradient with `on_primary` text.
*   **Secondary Action:** Ghost style using the "Ghost Border" fallback or a `surface_variant` background.
*   **Tertiary/Text:** No background, `primary` bold text, slight `primary_container` tint on hover.

### Input Fields
*   **Surface:** `surface_container_lowest`.
*   **Radius:** 12px (md).
*   **State:** On focus, transition the "Ghost Border" to a 2px `primary` stroke and add a soft 8px `primary` ambient glow.

### Cards & Data Lists
*   **Card Radius:** 24px (2xl).
*   **No Dividers:** Forbid the use of 1px lines between list items. Use 16px–24px of vertical white space or alternating `surface_container_low` / `surface_container_lowest` strips to define rows.
*   **Cultural Flourish:** Use `secondary` (Amber) as a "highlighter" for status tags (e.g., "Pending" or "Featured") to inject warmth into data tables.

### Navigation (Glass Header)
*   **Component:** Top bar or Side rail.
*   **Style:** `surface_bright` with 70% opacity and 32px blur. This ensures the colorful "African-inspired" accents of the content bleed through as the user scrolls.

---

## 6. Do’s and Don’ts

### Do
*   **Do** embrace negative space. Large margins (32px+) reinforce the premium feel.
*   **Do** use asymmetrical layouts. For example, a wider left column for content and a narrower, glassmorphic right column for metadata.
*   **Do** use the `secondary_container` (Amber) for moments of delight, like success states or badge notifications.

### Don’t
*   **Don’t** use a standard 8px corner radius. This system requires the 20px–24px "pill-card" look to maintain its signature identity.
*   **Don’t** use pure black (#000000) for text. Use `on_surface` (#1a1b20) to maintain tonal softness.
*   **Don’t** stack more than three layers of surfaces. If you need more depth, use a Backdrop Blur rather than another solid color.

---

## 7. Accessibility Note
While we prioritize "No-Line" design, always ensure that text ratios meet WCAG AA standards. Use the `on_surface_variant` for helper text only if it meets the 4.5:1 ratio against the container color. If a surface transition is too subtle for low-vision users, the "Ghost Border" (at 20% opacity) is your primary tool for structural reinforcement.```