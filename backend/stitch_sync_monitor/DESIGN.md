# Design System Specification: The Cultural Curator

This design system is a bespoke framework crafted for a high-end SaaS experience that bridges the gap between modern technology and rich heritage. It is designed to feel editorial, intentional, and premium, moving away from "standard" dashboard templates toward a signature aesthetic characterized by depth, light, and motion.

---

### 1. Overview & Creative North Star: "The Digital Curator"
The Creative North Star for this system is **"The Digital Curator."** 

Unlike traditional SaaS platforms that rely on rigid, boxy structures, this system treats the interface as a gallery. We break the "template" look by utilizing intentional asymmetry, overlapping elements, and high-contrast typography scales. The goal is to create a UI that breathes—using whitespace as a functional element and glassmorphism to create a sense of physical layers in a digital space. The "Modern African-inspired" influence manifests through vibrant tonal depth and organic roundedness, rather than stereotypical patterns.

---

### 2. Colors & Surface Philosophy

The palette is rooted in nature’s most vibrant transitions: the deep greens of the rainforest (Emerald), the warmth of the sub-Saharan sun (Amber), and the regal twilight (Violet).

#### The Color Roles
*   **Primary (Emerald):** Used for growth, success, and main actions. Transitioning from `#006948` (Deep) to `#68dba9` (Lime).
*   **Secondary (Amber):** Used for warnings, highlights, and secondary attention. Transitioning from `#904d00` to `#fe932c`.
*   **Tertiary (Violet):** Used for deep accents, creative insights, and special categories. Transitioning from `#712ae2` to `#d2bbff`.

#### The "No-Line" Rule
Standard 1px solid borders are strictly prohibited for sectioning. Boundaries must be defined solely through:
1.  **Background Color Shifts:** Placing a `surface_container_low` element against a `surface` background.
2.  **Shadows:** Using ambient depth to imply a border.
3.  **Tonal Transitions:** Using gradients to define the edge of a component.

#### The "Glass & Gradient" Rule
To ensure a high-end feel, all primary surfaces should utilize **Glassmorphism**. Use semi-transparent versions of `surface_container_lowest` with a `backdrop-blur` of 20px–40px. 
*   **Signature Textures:** Apply subtle linear gradients (Primary to Primary-Container) to CTAs and Hero sections. This adds "soul" and prevents the UI from feeling flat or clinical.

---

### 3. Typography: Editorial Authority

The system uses a dual-font approach to balance character with readability.

*   **Display & Headline (Manrope):** Our "Voice." Bold, geometric, and authoritative. Use `display-lg` (3.5rem) for high-impact editorial moments and `headline-sm` (1.5rem) for section titles. The wide aperture of Manrope evokes a modern, global feel.
*   **Body & Labels (Inter):** Our "Engine." Selected for its extreme legibility at small sizes. 
    *   **Body-md (0.875rem):** The workhorse for all dashboard data.
    *   **Label-sm (0.6875rem):** Used for metadata, ensuring the UI remains clean and uncluttered.

**Hierarchy Tip:** Maintain a minimum 2x size ratio between headlines and body text to create the "Editorial" look.

---

### 4. Elevation & Depth: Tonal Layering

We avoid traditional "material" shadows in favor of **Tonal Layering**.

*   **The Layering Principle:** Depth is achieved by stacking `surface-container` tiers. 
    *   *Base:* `surface` (`#faf8ff`)
    *   *Section:* `surface_container_low` (`#f2f3ff`)
    *   *Card:* `surface_container_lowest` (`#ffffff`)
*   **Ambient Shadows:** When an element must "float" (e.g., a modal or a floating action button), use a shadow with a blur of `40px`, an opacity of `6%`, and a color tinted with `on_surface` (`#131b2e`). This mimics natural, soft light.
*   **The "Ghost Border" Fallback:** If a container needs more definition, use a `1px` stroke of `outline_variant` at **15% opacity**. Never use 100% opaque strokes.

---

### 5. Components

#### Buttons: The Gradient Lift
*   **Primary:** A gradient from `primary` to `primary_container`. **Radius: xl (1.5rem)**. 
*   **Interaction:** On hover, the button should "lift" using a soft shadow and a subtle `scale(1.02)`.
*   **Tertiary:** No background, `primary` text, with a `surface_container_high` background appearing only on hover.

#### Cards: The Glass Plate
*   Forbid all divider lines within cards. Use spacing (`spacing.6`) to separate header from content.
*   **Visuals:** `surface_container_lowest` at 80% opacity + 20px backdrop-blur. 
*   **Edges:** Use a `2xl` corner radius (`1.5rem` or higher) to keep the aesthetic friendly and modern.

#### Inputs: The Modern Field
*   **Default:** `surface_container_low` background with no border. 
*   **Focus:** A subtle gradient border or a 2px stroke of `primary_fixed`.
*   **Typography:** Use `label-md` for labels, positioned 8px above the field.

#### Tables: Spaced & Organic
*   **Rule:** No vertical or horizontal lines. 
*   **Pattern:** Use zebra-striping with `surface_container_low` and `surface_container_lowest`. 
*   **Padding:** Use `spacing.4` for cell density to allow the data to breathe.

---

### 6. Do’s and Don’ts

#### Do
*   **Do** use asymmetrical layouts (e.g., a wider left column and a narrow right column) to break the "grid" feel.
*   **Do** use gradient fills for charts (e.g., `primary` fading to `surface_container_lowest`).
*   **Do** use the `2xl` roundedness scale for all main containers.

#### Don’t
*   **Don’t** use pure black `#000000` or pure grey. Always use the tinted neutrals (`surface_variant`, `on_surface`).
*   **Don’t** use 1px solid dividers to separate content. Use whitespace or tonal shifts.
*   **Don’t** cram data. If a dashboard feels crowded, increase the spacing scale by one increment (e.g., move from `spacing.4` to `spacing.5`).

---

### 7. Token Reference Summary

| Category | Token | Value |
| :--- | :--- | :--- |
| **Radius** | XL / 2XL | 1.5rem / 2rem |
| **Shadow** | Ambient | 0 20px 40px rgba(19, 27, 46, 0.06) |
| **Glass** | Blur / Opacity | 24px / 80% Surface |
| **Spacing** | Standard Gap | 1.4rem (Scale 4) |
| **Typography**| Primary Font | Manrope (Headings) / Inter (Body) |