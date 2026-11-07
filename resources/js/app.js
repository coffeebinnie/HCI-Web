const closeModal = (modal) => {
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
};

const openModal = (id) => {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    const focusable = modal.querySelector('input:not([type="hidden"]), select, textarea, button');
    window.setTimeout(() => focusable?.focus(), 60);
};

document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-modal-open]');
    if (opener) {
        event.preventDefault();
        openModal(opener.dataset.modalOpen);
        return;
    }

    const closer = event.target.closest('[data-modal-close]');
    if (closer) {
        event.preventDefault();
        closeModal(closer.closest('[data-modal]'));
        return;
    }

    const backdrop = event.target.closest('[data-modal]');
    if (backdrop && event.target === backdrop) {
        closeModal(backdrop);
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('[data-modal]:not(.hidden)').forEach(closeModal);
    }
});

// Drawer sidebar for small screens
const sidebar = document.getElementById('app-sidebar');
const sidebarBackdrop = document.getElementById('sidebar-backdrop');

const toggleSidebar = (force) => {
    if (!sidebar) return;
    const willOpen = typeof force === 'boolean' ? force : sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !willOpen);
    sidebar.classList.toggle('translate-x-0', willOpen);
    sidebarBackdrop?.classList.toggle('hidden', !willOpen);
};

document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
    button.addEventListener('click', () => toggleSidebar());
});
sidebarBackdrop?.addEventListener('click', () => toggleSidebar(false));

// Transaction type segmented control
document.querySelectorAll('[data-type-group]').forEach((group) => {
    const buttons = group.querySelectorAll('[data-type]');
    const hidden = document.querySelector(group.dataset.typeGroup);

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            buttons.forEach((item) => {
                const active = item === button;
                item.setAttribute('aria-pressed', String(active));
                item.classList.toggle('border-primary', active);
                item.classList.toggle('bg-primary/15', active);
                item.classList.toggle('text-fg', active);
                item.classList.toggle('border-line', !active);
                item.classList.toggle('text-muted', !active);
                const dot = item.querySelector('[data-dot]');
                if (dot) {
                    dot.textContent = active ? '◉' : '○';
                }
            });
            if (hidden) {
                hidden.value = button.dataset.type;
            }
        });
    });
});

// Accessible switches
document.querySelectorAll('[data-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        toggle.setAttribute('aria-checked', toggle.getAttribute('aria-checked') === 'true' ? 'false' : 'true');
    });
});

// Reveal / hide password fields
document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
        button.textContent = input.type === 'password' ? 'Show password' : 'Hide password';
    });
});

// Fill the demo credentials on the login screen
document.querySelectorAll('[data-demo-login]').forEach((button) => {
    button.addEventListener('click', () => {
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        if (email) {
            email.value = button.dataset.demoEmail;
        }

        if (password) {
            password.value = button.dataset.demoPassword;
        }

        email?.focus();
    });
});

// Persist the active nav highlight based on the current path
const path = window.location.pathname;
document.querySelectorAll('[data-nav-match]').forEach((link) => {
    const matches = link.dataset.navMatch.split('|').some((route) => path === route || path.startsWith(`${route}/`));
    if (matches) {
        link.classList.add('nav-link-active');
    }
});

// Prepare create/edit modals for budgets, savings goals, and contributions
const setFormMode = (form, submit, title, createTitle, editTitle, editing) => {
    if (!form) return;
    form.querySelector('input[name="_method"]').value = editing ? 'PUT' : 'POST';
    form.action = editing ? form.dataset.editUrl : form.dataset.storeUrl;
    if (submit) submit.textContent = editing ? editTitle[0] : createTitle[0];
    if (title) title.textContent = editing ? editTitle[1] : createTitle[1];
};

// Budgets
const budgetForm = document.getElementById('budget-form');
const budgetSubmit = document.getElementById('budget-submit');
const budgetTitle = document.getElementById('add-budget-title');

const resetBudgetForm = () => {
    if (!budgetForm) return;
    budgetForm.reset();
    setFormMode(budgetForm, budgetSubmit, budgetTitle, ['Save Budget', 'Add Budget'], ['Update Budget', 'Edit Budget'], false);
};

document.querySelectorAll('[data-budget-add]').forEach((button) => {
    button.addEventListener('click', resetBudgetForm);
});

document.querySelectorAll('[data-budget-edit]').forEach((button) => {
    button.addEventListener('click', () => {
        if (!budgetForm) return;

        budgetForm.dataset.editUrl = button.dataset.action;
        document.getElementById('budget-category').value = button.dataset.category;
        document.getElementById('budget-limit').value = button.dataset.limit;
        document.getElementById('budget-month').value = button.dataset.month;
        document.getElementById('budget-year').value = button.dataset.year;
        setFormMode(budgetForm, budgetSubmit, budgetTitle, ['Save Budget', 'Add Budget'], ['Update Budget', 'Edit Budget'], true);
    });
});

// Savings goals
const goalForm = document.getElementById('goal-form');
const goalSubmit = document.getElementById('goal-submit');
const goalTitle = document.getElementById('add-goal-title');

const resetGoalForm = () => {
    if (!goalForm) return;
    goalForm.reset();
    setFormMode(goalForm, goalSubmit, goalTitle, ['Save Goal', 'Create Savings Goal'], ['Update Goal', 'Edit Savings Goal'], false);
};

document.querySelectorAll('[data-goal-add]').forEach((button) => {
    button.addEventListener('click', resetGoalForm);
});

document.querySelectorAll('[data-goal-edit]').forEach((button) => {
    button.addEventListener('click', () => {
        if (!goalForm) return;

        goalForm.dataset.editUrl = button.dataset.action;
        document.getElementById('goal-name').value = button.dataset.name;
        document.getElementById('goal-amount').value = button.dataset.target;
        document.getElementById('goal-date').value = button.dataset.targetDate || '';
        document.getElementById('goal-description').value = button.dataset.description || '';
        setFormMode(goalForm, goalSubmit, goalTitle, ['Save Goal', 'Create Savings Goal'], ['Update Goal', 'Edit Savings Goal'], true);
    });
});

// Contributions
const contributionForm = document.getElementById('contribution-form');
const contributionSubmit = document.getElementById('contribution-submit');
const contributionTitle = document.getElementById('add-contribution-title');

const resetContributionForm = () => {
    if (!contributionForm) return;
    contributionForm.reset();
    setFormMode(
        contributionForm,
        contributionSubmit,
        contributionTitle,
        ['Add Contribution', 'Add Contribution'],
        ['Update Contribution', 'Edit Contribution'],
        false,
    );
};

document.querySelectorAll('[data-contribution-add]').forEach((button) => {
    button.addEventListener('click', () => {
        resetContributionForm();
        if (button.dataset.goalId) {
            document.getElementById('contribution-goal').value = button.dataset.goalId;
        }
    });
});

document.querySelectorAll('[data-contribution-edit]').forEach((button) => {
    button.addEventListener('click', () => {
        if (!contributionForm) return;

        contributionForm.dataset.editUrl = button.dataset.action;
        document.getElementById('contribution-goal').value = button.dataset.goalId;
        document.getElementById('contribution-amount').value = button.dataset.amount;
        document.getElementById('contribution-date').value = button.dataset.date;
        document.getElementById('contribution-source').value = button.dataset.source || '';
        document.getElementById('contribution-notes').value = button.dataset.notes || '';
        setFormMode(
            contributionForm,
            contributionSubmit,
            contributionTitle,
            ['Add Contribution', 'Add Contribution'],
            ['Update Contribution', 'Edit Contribution'],
            true,
        );
    });
});
