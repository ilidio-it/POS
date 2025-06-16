# Cantina Smart Sales System

## Deployment Guide

### Prerequisites
1. Supabase Account
2. Vercel Account (or any other hosting platform)
3. Node.js v18 or higher

### Database Setup

1. Create a new Supabase project
2. Go to Project Settings > Database
3. Copy the database connection string
4. Execute the migration file located in `supabase/migrations/20250518142048_wooden_ember.sql`

### Environment Variables

Create a `.env` file with the following variables:

```env
VITE_SUPABASE_URL=your_supabase_url
VITE_SUPABASE_ANON_KEY=your_supabase_anon_key
```

### Local Development

1. Install dependencies:
```bash
npm install
```

2. Start development server:
```bash
npm run dev
```

### Deployment Steps

1. Push your code to a Git repository

2. Connect to Vercel:
   - Create new project
   - Import your repository
   - Add environment variables:
     - VITE_SUPABASE_URL
     - VITE_SUPABASE_ANON_KEY
   - Deploy

### Database Schema

The application uses the following tables:

1. users
   - Stores user accounts and roles
   - Authentication handled by Supabase Auth

2. products
   - Inventory management
   - Stock tracking
   - Pricing information

3. sales
   - Transaction records
   - Customer information
   - Payment details

4. student_accounts
   - Student/Teacher balance management
   - Account types and transactions

5. debts
   - Debt tracking
   - Payment history
   - Status management

6. expenses
   - Operational expenses
   - Categorized spending
   - Expense tracking

### Security Considerations

1. Row Level Security (RLS) is enabled on all tables
2. User roles:
   - admin: Full access
   - manager: Limited administrative access
   - caixa: Basic sales operations

### Backup and Maintenance

1. Regular database backups:
   - Use Supabase dashboard
   - Schedule automatic backups
   - Store backup files securely

2. System updates:
   - Keep dependencies updated
   - Monitor security advisories
   - Test updates in staging

### Support and Troubleshooting

For issues:
1. Check application logs
2. Verify database connectivity
3. Confirm environment variables
4. Check user permissions

### Data Migration

To migrate existing data:
1. Export from current system
2. Transform to match schema
3. Import using provided SQL scripts
4. Verify data integrity