package com.mallchain.wallet.activity;

import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.ImageView;

import com.mallchain.wallet.MyApplication;
import com.mallchain.wallet.R;
import com.mallchain.wallet.fragents.ImportKeystoreFragment;
import com.mallchain.wallet.fragents.ImportKeystoreScanFragment;

/**
 * Created by Administrator on 2018/8/1.
 */
public class ImportKeystoreActivity extends AppCompatActivity {

    private ViewPager mViewPager;
    public Fragment[] fragments;
    private TabLayout tabLayout;
    SectionsPagerAdapter mSectionsPagerAdapter;
    private ImageView back;
    public String keystore;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_importkeystore);
        MyApplication.addActivity(this);
        back = (ImageView) findViewById(R.id.back);
        fragments = new Fragment[2];
        fragments[0] = new ImportKeystoreFragment();
        fragments[1] = new ImportKeystoreScanFragment();

        keystore = getIntent().getStringExtra("keystore");

        mSectionsPagerAdapter = new SectionsPagerAdapter(getSupportFragmentManager());
        mViewPager = (ViewPager) findViewById(R.id.container);
        mViewPager.setAdapter(mSectionsPagerAdapter);

        tabLayout = (TabLayout) findViewById(R.id.tabs);
        tabLayout.setupWithViewPager(mViewPager);
        tabLayout.setTabTextColors(getResources().getColor(R.color.color_9DA1A4), getResources().getColor(R.color.color_fa83bb));

        tabLayout.getTabAt(0).setText(getString(R.string.keystore));
        tabLayout.getTabAt(1).setText(getString(R.string.qrCode));

        back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                finish();
            }
        });

    }
    public class SectionsPagerAdapter extends FragmentPagerAdapter {

        public SectionsPagerAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public Fragment getItem(int position) {
            return fragments[position];
        }

        @Override
        public int getCount() {
            return fragments.length;
        }

        @Override
        public CharSequence getPageTitle(int position) {
            return "";
        }
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        MyApplication.removeActivity(this);
    }
}
