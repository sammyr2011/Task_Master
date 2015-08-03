package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.ProgressBar;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;
import java.util.concurrent.ExecutionException;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link com.example.lappy.taskmasterphase1.CategoryListingFragment.OnSelectCategoryListener} interface
 * to handle interaction events.
 * Use the {@link CategoryListingFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class CategoryListingFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    private OnSelectCategoryListener mListener;

    private ArrayAdapter mAdapter;

    private List<Category> mCategories;

    private ProgressBar progressBar;

    private ListView mCategoryListView;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment CategoryListingFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static CategoryListingFragment newInstance(String param1, String param2) {
        CategoryListingFragment fragment = new CategoryListingFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    public CategoryListingFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_category_listing, container, false);

        progressBar = (ProgressBar)view.findViewById(R.id.progressBar);

        mCategories = new ArrayList<Category>();
        mAdapter = new ArrayAdapter<Category>(view.getContext(),android.R.layout.simple_list_item_1,mCategories);

        final ListView categoryListView = (ListView)view.findViewById(R.id.listViewCategories);
        categoryListView.setAdapter(mAdapter);
        categoryListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Category selected = (Category) categoryListView.getItemAtPosition(position);
                mListener.onSelectCategory(selected.getId(), selected.toString());
            }
        });

        new GetCategories().execute();

        return view;
    }


    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);

        try {
            mListener = (OnSelectCategoryListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnSelectCategoryListener");
        }

    }

    @Override
    public void onDetach() {
        super.onDetach();
        mListener = null;
    }

    /**
     * This interface must be implemented by activities that contain this
     * fragment to allow an interaction in this fragment to be communicated
     * to the activity and potentially other fragments contained in that
     * activity.
     * <p/>
     * See the Android Training lesson <a href=
     * "http://developer.android.com/training/basics/fragments/communicating.html"
     * >Communicating with Other Fragments</a> for more information.
     */
    public interface OnSelectCategoryListener {
        // TODO: Update argument type and name
        public void onSelectCategory(int id, String name);
    }

    protected class GetCategories extends AsyncTask<Void, Void, List<Category>>
    {
        @Override
        protected List<Category> doInBackground(Void... params) {
            try {
                HttpClient client = MainActivity.getmHttpClient();
                HttpPost post = new HttpPost(getString(R.string.url_category_list));
                HttpResponse resp = client.execute(post);
                HttpEntity entity = resp.getEntity();

                JSONArray jsonArray = new JSONArray(EntityUtils.toString(entity));
                List<Category> categories = new ArrayList<Category>();
                for(int i=0; i<jsonArray.length();i++)
                {
                    JSONObject jsonCategory = jsonArray.getJSONObject(i);
                    Category tmp = new Category();
                    if(jsonCategory.has("CategoryName") && !jsonCategory.getString("CategoryName").equals("null"))
                    {
                        tmp.setTitle(jsonCategory.getString("CategoryName"));
                    }
                    if(jsonCategory.has("CategoryID") && !jsonCategory.getString("CategoryID").equals("null"))
                    {
                        tmp.setId(jsonCategory.getInt("CategoryID"));
                    }
                    categories.add(tmp);
                    //categories.add(new Category("test",5));
                }
                return categories;
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            progressBar.setVisibility(View.VISIBLE);
        }

        @Override
        protected void onPostExecute(List<Category> categories) {
            super.onPostExecute(categories);
            progressBar.setVisibility(View.INVISIBLE);
            //mAdapter = new ArrayAdapter<Category>(getView().getContext(),android.R.layout.simple_list_item_1,categories);
            //mCategories = categories;
            if(mCategories!=null && categories !=null) {
                mCategories.addAll(categories);
                Collections.sort(mCategories, new Comparator<Category>() {
                    @Override
                    public int compare(Category lhs, Category rhs) {
                        return String.CASE_INSENSITIVE_ORDER.compare(lhs.toString(), rhs.toString());
                    }
                });
            }
            mAdapter.notifyDataSetChanged();
        }
    }

}
