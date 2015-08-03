package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.ActionBar;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.support.v4.widget.DrawerLayout;
import android.widget.Toast;

import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;

import java.io.IOException;
import java.util.concurrent.ExecutionException;


public class MainActivity extends ActionBarActivity
        implements NavigationDrawerFragment.NavigationDrawerCallbacks,
        LoginFragment.OnLoginInteractionListener,
        CreateTaskFragment.OnCreateTaskListener,
        RegisterFragment.RegistrationListener,
        CategoryListingFragment.OnSelectCategoryListener,
        TaskListingFragment.OnTaskListingInteraction,
        ViewTaskFragment.OnPlaceBidListener,
        ViewTaskFragment.OnViewUserListener,
        ViewUserFragment.OnViewUserListener,
        LeaveFeedbackFragment.OnLeaveFeedbackListener,
        MessageListFragment.OnMessageListListener,
        ViewMessageFragment.OnViewMessageListener,
        SendMessageFragment.OnSendMessageInteractionListener
{

    /**
     * Fragment managing the behaviors, interactions and presentation of the navigation drawer.
     */
    private NavigationDrawerFragment mNavigationDrawerFragment;

    /**
     * Used to store the last screen title. For use in {@link #restoreActionBar()}.
     */
    private CharSequence mTitle;

    private static String mUserName="";


    private static boolean mLoggedIn=false;

    private static int mUserID=-1;

    Fragment mRegisterFragment;
    Fragment mLoginFragment;

    private static HttpClient mHttpClient;

    @Override
    public void onLoginInteraction(String login) {
        if(login.equals("login"))
        {
            mNavigationDrawerFragment.changeToLoggedInMenu();
            FragmentManager fragmentManager = getSupportFragmentManager();
            fragmentManager.beginTransaction().remove(mLoginFragment).commit();
            fragmentManager.popBackStackImmediate();
            onNavigationDrawerItemSelected(0);
            mLoggedIn=true;


            System.err.println("logged in");
        }
        else if (login.equals("register"))
        {
            FragmentManager fragmentManager = getSupportFragmentManager();
            mRegisterFragment = new RegisterFragment();
            FragmentTransaction regTrans = fragmentManager.beginTransaction();
            regTrans.replace(R.id.container,mRegisterFragment);
            regTrans.addToBackStack("reggie");
            regTrans.commit();
        }
    }

    @Override
    public void onRegistrationInteraction(String username, String password) {
        FragmentManager fragmentManager = getSupportFragmentManager();
        fragmentManager.beginTransaction().remove(mRegisterFragment).commit();
        fragmentManager.beginTransaction().remove(mLoginFragment).commit();
        mLoginFragment = new LoginFragment().newInstance(username, password);
        FragmentTransaction trans = fragmentManager.beginTransaction().replace(R.id.container, mLoginFragment);
        trans.addToBackStack("loggie");
        trans.commit();
    }

    @Override
    public void onSendMessageFromViewUser(int userid, String username)
    {
        if(mLoggedIn)
        {
            FragmentManager fragmentManager = getSupportFragmentManager();
            FragmentTransaction fragTrans = fragmentManager.beginTransaction().replace(R.id.container, new SendMessageFragment().newInstance(userid, username));
            fragTrans.addToBackStack("sendmessage");
            fragTrans.commit();
        }
        else
        {
            Toast.makeText(this,"You must be logged in to send a message.", Toast.LENGTH_LONG).show();
        }
    }

    @Override
    public void onViewTaskFromViewUser(int taskid) {
        FragmentManager fragmentManager = getSupportFragmentManager();
        FragmentTransaction fragTrans = fragmentManager.beginTransaction().replace(R.id.container, new ViewTaskFragment().newInstance(taskid));
        fragTrans.addToBackStack("viewtask");
        fragTrans.commit();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        mNavigationDrawerFragment = (NavigationDrawerFragment)
                getSupportFragmentManager().findFragmentById(R.id.navigation_drawer);
        mTitle = getTitle();

        // Set up the drawer.
        mNavigationDrawerFragment.setUp(
                R.id.navigation_drawer,
                (DrawerLayout) findViewById(R.id.drawer_layout));

        if(mLoggedIn)
        {
            mNavigationDrawerFragment.changeToLoggedInMenu();
        }
        else
        {
            mNavigationDrawerFragment.changeToLoggedOutMenu();
        }

        mHttpClient = new DefaultHttpClient();

    }

    @Override
    public void onNavigationDrawerItemSelected(int position) {
        // update the main content by replacing fragments
        FragmentManager fragmentManager = getSupportFragmentManager();


        if(!mLoggedIn)
        {
            switch(position)
            {
                case 0: //Home
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container, new HomeFragment()).commit();
                    break;
                case 1: //Browse by category
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container, new CategoryListingFragment()).commit();
                    break;
                case 2: //LOGIN
                    mLoginFragment = new LoginFragment();
                    FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction().replace(R.id.container, mLoginFragment);
                    fragmentTransaction.addToBackStack("loggie");
                    fragmentTransaction.commit();
                    break;
                default:
                    fragmentManager.beginTransaction()
                            .replace(R.id.container, PlaceholderFragment.newInstance(position + 1))
                            .commit();
                    break;
            }
        }
        else
        {
            switch(position)
            {
                case 0: //Home
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container, new HomeFragment()).commit();
                    break;
                case 1: //View account
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container, new ViewAccountFragment()).commit();
                    break;
                case 2: //Messages
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container,new MessageListFragment()).commit();
                    break;
                case 3: //My listings
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container, new TaskListingFragment().newInstance(mUserID)).commit();
                    break;
               /* case 4: //My bids
                    break;*/
                case 4: //Create task
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container, new CreateTaskFragment()).commit();
                    System.err.println("create task");
                    break;
                case 5: // Browse by category
                    clearBackstack();
                    fragmentManager.beginTransaction().replace(R.id.container, new CategoryListingFragment()).commit();
                    break;
                case 6: // Log Out
                    clearBackstack();
                    try {
                        new LogOut().execute("");
                    } catch (Throwable e) {
                        e.printStackTrace();
                    }
                    mLoggedIn=false;
                    mUserID=-1;
                    mUserName="";
                    mNavigationDrawerFragment.changeToLoggedOutMenu();
                    System.err.println("logged out");
                    onNavigationDrawerItemSelected(0);
                    break;
                default:
                    fragmentManager.beginTransaction()
                            .replace(R.id.container, PlaceholderFragment.newInstance(position + 1))
                            .commit();
            }
        }



    }



    public void onSectionAttached(int number) {
        String[] menuText;
        if(mLoggedIn)
        {
            menuText = getResources().getStringArray(R.array.array_logged_in_menu);
        }
        else
        {
            menuText = getResources().getStringArray(R.array.array_logged_out_menu);
        }

        if(number <= menuText.length)
        {
            mTitle = menuText[number-1];
        }
        else
        {
            mTitle = "whoops";
        }
    }

    public void restoreActionBar() {
        ActionBar actionBar = getSupportActionBar();
        actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_STANDARD);
        actionBar.setDisplayShowTitleEnabled(true);
        actionBar.setTitle(mTitle);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        if (!mNavigationDrawerFragment.isDrawerOpen()) {
            // Only show items in the action bar relevant to this screen
            // if the drawer is not showing. Otherwise, let the drawer
            // decide what to show in the action bar.
            getMenuInflater().inflate(R.menu.main, menu);
            restoreActionBar();
            return true;
        }
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onCreateTaskInteraction() {

        onNavigationDrawerItemSelected(3);
    }

    @Override
    public void onSelectCategory(int id, String catname) {
        FragmentManager fragmentManager = getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction().replace(R.id.container, new TaskListingFragment().newInstance(id,catname)).addToBackStack("tasklist");
        fragmentTransaction.commit();
    }

    @Override
    public void onSelectTask(int taskid) {
        FragmentManager fragmentManager = getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction().replace(R.id.container, new ViewTaskFragment().newInstance(taskid)).addToBackStack("viewtask");
        fragmentTransaction.commit();
    }

    @Override
    public void onPlaceBid(boolean loggedIn) {
        if(!loggedIn)
        {
            Toast.makeText(this,"You must log in before placing a bid!",Toast.LENGTH_LONG).show();
        }
        else
        {
            Toast.makeText(this,"Bid placed successfully!",Toast.LENGTH_LONG).show();
        }
    }

    @Override
    public void leaveFeedback(int revieweeuid, int taskid, String username, boolean listerOrDoer)
    {
        FragmentManager fragmentManager = getSupportFragmentManager();
        FragmentTransaction fragTrans = fragmentManager.beginTransaction();
        fragTrans.replace(R.id.container,new LeaveFeedbackFragment().newInstance(revieweeuid, taskid,username,listerOrDoer));
        fragTrans.addToBackStack("feedback");
        fragTrans.commit();
    }

    @Override
    public void onViewUser(int userid) {
        FragmentManager fragmentManagaer = getSupportFragmentManager();
        FragmentTransaction userFragTrans = fragmentManagaer.beginTransaction().replace(R.id.container, new ViewUserFragment().newInstance(userid));
        userFragTrans.addToBackStack("viewuser");
        userFragTrans.commit();
    }

    @Override
    public void onFeedbackSuccess() {
        FragmentManager fragmentManager = getSupportFragmentManager();
        fragmentManager.popBackStack("feedback",0);
        Toast.makeText(this,"Feedback successfully submitted!",Toast.LENGTH_LONG).show();
    }

    @Override
    public void onOpenMessage(Message m) {
        FragmentManager fragmentManager = getSupportFragmentManager();
        FragmentTransaction fragTrans = fragmentManager.beginTransaction().replace(R.id.container, new ViewMessageFragment().newInstance(m));
        fragTrans.addToBackStack("viewmessage");
        fragTrans.commit();
        System.err.println("main hears your click!");
    }

    @Override
    public void onSendReply(int uid, String uname) {
        onSendMessageFromViewUser(uid, uname);
    }

    @Override
    public void onViewTask(int taskid) {
        FragmentManager fragmentManager = getSupportFragmentManager();
        FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction().replace(R.id.container,new ViewTaskFragment().newInstance(taskid)).addToBackStack("viewtask");
        fragmentTransaction.commit();
    }

    @Override
    public void onMessageSent(Uri uri) {
        Toast.makeText(this,"Message sent!",Toast.LENGTH_LONG).show();
        getSupportFragmentManager().popBackStack("sendmessage",0);
    }


    /**
     * A placeholder fragment containing a simple view.
     */
    public static class PlaceholderFragment extends Fragment {
        /**
         * The fragment argument representing the section number for this
         * fragment.
         */
        private static final String ARG_SECTION_NUMBER = "section_number";

        /**
         * Returns a new instance of this fragment for the given section
         * number.
         */
        public static PlaceholderFragment newInstance(int sectionNumber) {
            PlaceholderFragment fragment = new PlaceholderFragment();
            Bundle args = new Bundle();
            args.putInt(ARG_SECTION_NUMBER, sectionNumber);
            fragment.setArguments(args);
            return fragment;
        }

        public PlaceholderFragment() {
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {
            View rootView = inflater.inflate(R.layout.fragment_main, container, false);
            return rootView;
        }

        @Override
        public void onAttach(Activity activity) {
            super.onAttach(activity);
            ((MainActivity) activity).onSectionAttached(
                    getArguments().getInt(ARG_SECTION_NUMBER));
        }
    }

    public static HttpClient getmHttpClient() {
        return mHttpClient;
    }

    public static void setmHttpClient(HttpClient inClient) {
        mHttpClient = inClient;
    }

    public static String getmUserName() {
        return mUserName;
    }

    public static void setmUserName(String inUserName) {
        mUserName = inUserName;
    }

    public static int getmUserID() {
        return mUserID;
    }

    public static void setmUserID(int inUserID) {
        mUserID = inUserID;
    }

    public static boolean ismLoggedIn() {
        return mLoggedIn;
    }

    private void clearBackstack()
    {
        FragmentManager fragmentManager = getSupportFragmentManager();
        for(int i=0;i<fragmentManager.getBackStackEntryCount(); i++)
        {
            fragmentManager.popBackStack();
        }
    }


    protected class LogOut extends AsyncTask<String, Void, String>
    {
        @Override
        protected String doInBackground(String... params) {
            logoutServer();
            return null;
        }

        protected void logoutServer()
        {
            HttpPost post = new HttpPost(getString(R.string.url_logout));
            try {
                getmHttpClient().execute(post);
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

}
