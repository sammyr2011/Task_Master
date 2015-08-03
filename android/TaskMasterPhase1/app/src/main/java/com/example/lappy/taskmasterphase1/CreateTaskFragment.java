package com.example.lappy.taskmasterphase1;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.provider.MediaStore;
import android.support.v4.app.Fragment;
import android.util.Base64;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TimePicker;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.concurrent.ExecutionException;


/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link CreateTaskFragment.OnCreateTaskListener} interface
 * to handle interaction events.
 * Use the {@link CreateTaskFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class CreateTaskFragment extends Fragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    private OnCreateTaskListener mListener;

    private HttpClient mHttpClient;

    private JSONArray mCategoryJSONArray;

    private ArrayList<String> mCategoryArrayList;
    private ArrayAdapter<String> mSpinnerAdapter;

    private static Bitmap mImageToUpload;
    private static String mImageFilepath;
    private static int IMAGE_SELECT= 322;

    /**
     * Use this factory method to create a new instance of
     * this fragment using the provided parameters.
     *
     * @param param1 Parameter 1.
     * @param param2 Parameter 2.
     * @return A new instance of fragment CreateTaskFragment.
     */
    // TODO: Rename and change types and number of parameters
    public static CreateTaskFragment newInstance(String param1, String param2) {
        CreateTaskFragment fragment = new CreateTaskFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    public CreateTaskFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }

        System.err.println("createtask oncreate called");
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_create_task_2, container, false);

        mHttpClient = MainActivity.getmHttpClient();

        try {
            new InitializeCategories().execute();
        } catch (Exception e) {
            e.printStackTrace();
        }

        Spinner categories = (Spinner)view.findViewById(R.id.spinnerCategory);

        //Initialize category array list
        mCategoryArrayList = new ArrayList<String>();
        //ArrayList<String> catArrayList = new ArrayList<String>();
        /*
        for(int i=0;i<mCategoryJSONArray.length();i++)
        {
            try {
                catArrayList.add(mCategoryJSONArray.getJSONObject(i).getString("CategoryName"));
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
        */
        //Set category spinner adapter
        mSpinnerAdapter = new ArrayAdapter<String>(view.getContext(), android.R.layout.simple_list_item_1,android.R.id.text1, mCategoryArrayList);
        categories.setAdapter(mSpinnerAdapter);
        //categories.setAdapter(new ArrayAdapter<String>(view.getContext(), android.R.layout.simple_list_item_1,android.R.id.text1, mCategoryArrayList));

        Button selectImage = (Button)view.findViewById(R.id.buttonCreateTaskUploadImage);
        selectImage.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent pickImage = new Intent(Intent.ACTION_GET_CONTENT);
                pickImage.setType("image/*");
                startActivityForResult(pickImage,IMAGE_SELECT);
            }
        });

        Button createTaskButton = (Button)view.findViewById(R.id.createTaskButton);
        createTaskButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    new SubmitTask().execute();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });

        System.err.println("createtask oncreateview caled");

        // Inflate the layout for this fragment
        return view;
    }


    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnCreateTaskListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnCreateTaskListener");
        }

        System.err.println("Createtask onattach called");
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        if(requestCode==IMAGE_SELECT && resultCode == Activity.RESULT_OK)
        {
            if(data!=null && data.getData()!=null)
            {
                Uri image = data.getData();
                Cursor cursor = getView().getContext().getContentResolver().query(image,null,null,null,null);
                if(cursor == null)
                {
                    mImageFilepath = image.getPath();
                }
                else
                {
                    cursor.moveToFirst();
                    int index = cursor.getColumnIndex(MediaStore.Images.ImageColumns.DATA);
                    mImageFilepath = cursor.getString(index);
                }

                try{
                    mImageToUpload = MediaStore.Images.Media.getBitmap(getView().getContext().getContentResolver(),image);
                    ImageView selectedImage = (ImageView)getView().findViewById(R.id.imageViewCreateTask);
                    selectedImage.setImageBitmap(mImageToUpload);
                    selectedImage.invalidate();
                }
                catch (Throwable t)
                {
                    t.printStackTrace();
                }

            }
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
    public interface OnCreateTaskListener {
        public void onCreateTaskInteraction();
    }

    protected class SubmitTask extends AsyncTask<String, Void, JSONObject>
    {
        ProgressBar loading;
        EditText taskTitle;
        EditText taskDescription;
        EditText location;
        Spinner category;
        EditText tags;
        DatePicker endDate;
        TimePicker endTime;

        @Override
        protected JSONObject doInBackground(String... params) {

            return submitTask();
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            taskTitle = (EditText)getView().findViewById(R.id.editTextTaskTitle);
            taskDescription = (EditText)getView().findViewById(R.id.editTextTaskDescription);
            location = (EditText)getView().findViewById(R.id.editTextLocation);
            category = (Spinner)getView().findViewById(R.id.spinnerCategory);
            endDate = (DatePicker)getView().findViewById(R.id.datePickerCreateTask);
            endTime = (TimePicker)getView().findViewById(R.id.timePickerCreateTask);
            loading = (ProgressBar)getView().findViewById(R.id.progressBarCreateTask);
            loading.setVisibility(View.VISIBLE);
        }

        @Override
        protected void onPostExecute(JSONObject jsonResponse) {
            super.onPostExecute(jsonResponse);
            loading.setVisibility(View.INVISIBLE);
            if(jsonResponse==null)
            {
                return;
            }
            if(jsonResponse.has("submitted"))
            {
                //do happy things
                mListener.onCreateTaskInteraction();
                System.err.println("gucci");
            }
            else
            {
                if(jsonResponse.has("title"))
                {
                    taskTitle.post(new Runnable() {
                        @Override
                        public void run() {
                            taskTitle.setError("Invalid title");
                        }
                    });
                    //taskTitle.setError("Invalid title");
                    System.err.println("invalid title");
                }
                if(jsonResponse.has("description"))
                {
                    taskDescription.post(new Runnable() {
                        @Override
                        public void run() {
                            taskDescription.setError("Invalid description");
                        }
                    });
                    //taskTitle.setError("Invalid description");
                    System.err.println("invalid desc");
                }
                if(jsonResponse.has("location"))
                {
                    location.post(new Runnable() {
                        @Override
                        public void run() {
                            location.setError("Invalid location");
                        }
                    });
                    //taskTitle.setError("Invalid location");
                    System.err.println("invalid loc");
                }
                if(jsonResponse.has("tags"))
                {
                    tags.post(new Runnable() {
                        @Override
                        public void run() {
                            tags.setError("Invalid tags");
                        }
                    });
                    //taskTitle.setError("Invalid tags");
                    System.err.println("invalid tags");
                }
            }
        }

        protected JSONObject submitTask()
        {
            HttpPost post = new HttpPost(getString(R.string.url_create_task));

            List<NameValuePair> nvp = new ArrayList<NameValuePair>();
            nvp.add(new BasicNameValuePair("title",taskTitle.getText().toString()));
            nvp.add(new BasicNameValuePair("description",taskDescription.getText().toString()));
            nvp.add(new BasicNameValuePair("content",taskDescription.getText().toString()));
            nvp.add(new BasicNameValuePair("location",location.getText().toString()));
            nvp.add(new BasicNameValuePair("category",Integer.toString(category.getSelectedItemPosition() + 1)));
            nvp.add(new BasicNameValuePair("tags","no more tags"));
            if(mImageFilepath!="" && mImageFilepath!=null)
            {
                File file = new File(mImageFilepath);
                ByteArrayOutputStream stream = new ByteArrayOutputStream();
                mImageToUpload.compress(Bitmap.CompressFormat.JPEG, 90, stream);
                byte[] arr = stream.toByteArray();
                String imgStr = Base64.encodeToString(arr, Base64.DEFAULT);
                nvp.add(new BasicNameValuePair("image", imgStr));
                //System.err.println(imgStr);
            }

            //Encode Unix time
            Calendar c = Calendar.getInstance();
            c.set(Calendar.YEAR, endDate.getYear());
            c.set(Calendar.MONTH, endDate.getMonth());
            c.set(Calendar.DAY_OF_MONTH, endDate.getDayOfMonth());
            c.set(Calendar.HOUR,endTime.getCurrentHour());
            c.set(Calendar.MINUTE,endTime.getCurrentMinute());
            //Add to nvp
            nvp.add(new BasicNameValuePair("enddatetime",Long.toString(c.getTimeInMillis()/1000)));
            System.err.println(Long.toString(c.getTimeInMillis()/1000));

            try {
                post.setEntity(new UrlEncodedFormEntity(nvp));
            } catch (UnsupportedEncodingException e) {
                e.printStackTrace();
            }

            HttpResponse resp;

            try {
                resp = mHttpClient.execute(post);
            } catch (IOException e) {
                e.printStackTrace();
                return null;
            }

            HttpEntity entity = resp.getEntity();

            JSONObject jsonResponse;



            try {
                String stringresp = EntityUtils.toString(entity);
                System.err.println(stringresp);
                jsonResponse = new JSONObject(stringresp);
                System.err.println(jsonResponse.toString());
                return jsonResponse;
            } catch (Exception e) {
                e.printStackTrace();
                return null;
            }


        }
    }

    protected class InitializeCategories extends AsyncTask<Void, Void, Void>
    {
        JSONArray aResult;

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            if(aResult!=null)
            {
                mCategoryJSONArray = aResult;
                for(int i=0;i<mCategoryJSONArray.length();i++)
                {

                    try {
                        if(mCategoryJSONArray.getJSONObject(i).has("CategoryName") && !mCategoryJSONArray.getJSONObject(i).getString("CategoryName").equals("null"))
                        {
                            mCategoryArrayList.add(mCategoryJSONArray.getJSONObject(i).getString("CategoryName"));
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }
            }
        }

        @Override
        protected Void doInBackground(Void... params) {
            getCats();
            return null;
        }

        protected void getCats()
        {
            HttpPost post = new HttpPost(getString(R.string.url_category_list));
            HttpResponse resp;

            try {
                resp = mHttpClient.execute(post);
            } catch (IOException e) {
                e.printStackTrace();
                return;
            }

            HttpEntity entity = resp.getEntity();

            JSONArray array;

            try {
                array = new JSONArray(EntityUtils.toString(entity));
            } catch (Exception e) {
                e.printStackTrace();
                return;
            }

            aResult = array;
        }
    }
}
